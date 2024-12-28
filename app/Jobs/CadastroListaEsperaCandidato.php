<?php

namespace App\Jobs;

use App\Models\Chamada;
use App\Models\Candidato;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\MultiplicadorVaga;
use App\Models\User;
use DateTime;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use League\Csv\Reader;


class CadastroListaEsperaCandidato implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $chamada;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Chamada $chamada)
    {
        $this->chamada = $chamada;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $csvPath = storage_path('app' . DIRECTORY_SEPARATOR . $this->chamada->sisu->caminho_import_espera);

        // Lendo o arquivo CSV
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $inscricoesData = [];
        $cont = 0;
        foreach ($records as $record) {
            //Armazenamos as informações de cada candidato
            $inscricoesData[] = [
                'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
                'protocolo' => Hash::make($record['NO_INSCRITO'] . $this->chamada->id),
                'nu_etapa' => $record['NU_ETAPA'],
                'no_campus' => $record['NO_CAMPUS'],
                'co_ies_curso' => $record['CO_IES_CURSO'],
                'no_curso' => $record['NO_CURSO'],
                'ds_turno' => $record['DS_TURNO'],
                'ds_formacao' => $record['DS_FORMACAO'],
                'qt_vagas_concorrencia' => $record['QT_VAGAS_CONCORRENCIA'],
                'co_inscricao_enem' => $record['CO_INSCRICAO_ENEM'],

                'no_inscrito' => $record['NO_INSCRITO'],
                'no_social' => $record['NO_SOCIAL'],
                'nu_cpf_inscrito' => $record['NU_CPF_INSCRITO'],
                'dt_nascimento' => DateTime::createFromFormat('Y-m-d H:i:s', $record['DT_NASCIMENTO'])->format('Y-m-d'),

                //'cd_efetivado' => false,
                'tp_sexo' => $record['TP_SEXO'],
                'nu_rg' => $record['NU_RG'],
                'no_mae' => $record['NO_MAE'],
                'ds_logradouro' => $record['DS_LOGRADOURO'],
                'nu_endereco' => $record['NU_ENDERECO'],
                'ds_complemento' => $record['DS_COMPLEMENTO'],
                'sg_uf_inscrito' => $record['SG_UF_INSCRITO'],
                'no_municipio' => $record['NO_MUNICIPIO'],
                'no_bairro' => $record['NO_BAIRRO'],
                'nu_cep' => $record['NU_CEP'],
                'nu_fone1' => $record['NU_FONE1'],
                'nu_fone2' => $record['NU_FONE2'],
                'ds_email' => $record['DS_EMAIL'],
                'nu_nota_l' => str_replace(',', '.', $record['NU_NOTA_L']),
                'nu_nota_ch' => str_replace(',', '.', $record['NU_NOTA_CH']),
                'nu_nota_cn' => str_replace(',', '.', $record['NU_NOTA_CN']),
                'nu_nota_m' => str_replace(',', '.', $record['NU_NOTA_M']),
                'nu_nota_r' => str_replace(',', '.', $record['NU_NOTA_R']),
                'co_curso_inscricao' => $record['CO_CURSO_INSCRICAO'],
                'st_opcao' => $record['ST_OPCAO'],
                'no_modalidade_concorrencia' => $record['NO_MODALIDADE_CONCORRENCIA'],
                'st_bonus_perc' => $record['ST_BONUS_PERC'],
                'qt_bonus_perc' => $record['QT_BONUS_PERC'],
                'no_acao_afirmativa_bonus' => $record['NO_ACAO_AFIRMATIVA_BONUS'],
                'nu_nota_candidato' => str_replace(',', '.', $record['NU_NOTA_CANDIDATO']),
                'nu_notacorte_concorrida' => str_replace(',', '.', $record['NU_NOTACORTE_CONCORRIDA']),
                'nu_classificacao' => $record['NU_CLASSIFICACAO'],
                'ds_matricula' => $record['DS_MATRICULA'],
                'dt_operacao' => DateTime::createFromFormat('Y-m-d H:i:s', $record['DT_OPERACAO'])->format('Y/m/d'),
                'co_ies' => $record['CO_IES'],
                'no_ies' => $record['NO_IES'],
                'sg_ies' => $record['SG_IES'],
                'sg_uf_ies' => $record['SG_UF_IES'],
                'st_lei_optante' => $record['ST_LEI_OPTANTE'],
                'st_lei_renda' => $record['ST_LEI_RENDA'],
                'st_lei_etnia_p' => $record['ST_LEI_ETNIA_P'],
                'st_lei_etnia_i' => $record['ST_LEI_ETNIA_I'],
                'de_acordo_lei_cota' => $record['DE_ACORDO_LEI_COTA'],
                'ensino_medio' => $record['ENSINO_MEDIO'],
                'etnia_e_cor' => $record['ETNIA_E_COR'],
                'quilombola' => $record['QUILOMBOLA'],
                'deficiente' => $record['DEFICIENTE'],
                'modalidade_escolhida' => $record['MODALIDADE_ESCOLHIDA'],
                'tipo_concorrencia' => $record['TIPO_CONCORRENCIA'],
            ];
            $cont += 1;
        }

        $inscricoes = collect($inscricoesData);

        //Agrupamos por curso
        $grouped = $inscricoes->groupBy(function ($candidato) {
            return $candidato['co_ies_curso'] . $candidato['ds_turno'];
        });
        $porCurso = collect();
        //E separamos por modalidade
        foreach ($grouped as $curso) {
            $porCurso->push($curso->groupBy('no_modalidade_concorrencia'));
        }

        $cursos = collect();

        //Collection para armazenar apenas as modalidades de cada curso que estão presentes no arquivo, para sabermos se há candidatos a
        //serem chamados daquela modalidade
        $cotasCursosCOD = collect();

        //Feito isto, é necessário juntar todos os inscritos da ampla concorrencia independente da cota de 10%
        foreach ($porCurso as $curso) {
            $modalidade = collect();
            $ampla = collect();
            $modalidades = collect();

            $cotaCOD = collect();
            $cotaAmpla = false;
            foreach ($curso as $porModalidade) {
                //os de ampla são colocados em um único collection aqui. Há várias verificações por inconsistência dos dados fornecidos
                if (
                    $porModalidade[0]['no_modalidade_concorrencia'] == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
                    $porModalidade[0]['no_modalidade_concorrencia'] == 'AMPLA CONCORRÊNCIA' || $porModalidade[0]['no_modalidade_concorrencia'] == 'Ampla concorrência'
                ) {
                    $ampla = $ampla->concat($porModalidade);
                    if (!$cotaAmpla) {
                        $cotaAmpla = true;
                    }
                } else {
                    $modalidade = $porModalidade;
                    if (!$cotaCOD->contains($modalidade[0]['no_modalidade_concorrencia'])) {
                        $cotaCOD->push($modalidade[0]['no_modalidade_concorrencia']);
                    }
                    $modalidade = $modalidade->sortBy(function ($candidato) {
                        return $candidato['nu_classificacao'];
                    });
                    $modalidades->push($modalidade);
                }
            }
            //ordenamos os inscritos da modalidade daquele curso pela classificacao
            $ampla = $ampla->sortBy(function ($candidato) {
                return $candidato['nu_classificacao'];
            });
            $modalidades->push($ampla);
            $cursos->push($modalidades);
            if ($cotaAmpla) {
                $cotaCOD->push(Cota::COD_COTA_ENUM['A0']);
            }
            $cotasCursosCOD->push($cotaCOD);
        }

        //Preparados os dados dos inscritos, agora criaremos as instancias para salvar no banco
        //Percorremos cada curso
        foreach ($cursos as $indexCurso => $curso) {
            $candidato = $curso[0][0];
            /*//Recuperamos a cota que aquele inscrito está relacionado
            if($candidato['no_modalidade_concorrencia'] == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
            $candidato['no_modalidade_concorrencia'] == 'Ampla concorrência' || $candidato['no_modalidade_concorrencia'] == 'AMPLA CONCORRÊNCIA'){
                $cota = Cota::where('descricao',  'Ampla concorrência')->first();
            }else{
                $cota = Cota::where('descricao', $candidato['no_modalidade_concorrencia'])->first();
            }
            //E pegamos a informação de quantas vagas temos tem restante baseado em quantos candidatos foram efetivados e quantas vagas são
            //ofertadas para aquela cota*/

            if ($candidato['ds_turno'] == 'Matutino') {
                $turno =  Curso::TURNO_ENUM['Matutino'];
            } elseif ($candidato['ds_turno'] == 'Vespertino') {
                $turno = Curso::TURNO_ENUM['Vespertino'];
            } elseif ($candidato['ds_turno'] == 'Noturno') {
                $turno = Curso::TURNO_ENUM['Noturno'];
            } elseif ($candidato['ds_turno'] == 'Integral') {
                $turno = Curso::TURNO_ENUM['Integral'];
            }

            //E recuperamos a instancia do curso do banco de dados
            $curs = Curso::where([['cod_curso', $candidato['co_ies_curso']], ['turno', $turno]])->first();

            /*Para a nova regra de chamadas da lista de espera, e necessario preencher o restante de vagas da ampla concorrencia
            com os candidatos com as maiores notas  daquele curso*/

            $candidatosCurso = collect();
            foreach ($cursos[$indexCurso] as $modalidadeAtual) {
                $candidatosCurso = $candidatosCurso->concat($modalidadeAtual->all());
            }

            $candidatosCurso = $candidatosCurso->sortByDesc(function ($candidato) {
                return $candidato['nu_nota_candidato'];
            });

            $A0 = Cota::where('cod_cota', 'A0')->first();
            $cota_cursoA0 = $curs->cotas()->where('cota_id', $A0->id)->where('sisu_id', $this->chamada->sisu->id)->first()->pivot;
            $vagasCotaA0 = $cota_cursoA0->quantidade_vagas - $cota_cursoA0->vagas_ocupadas;

            //chamamos o número de vagas disponíveis vezes o valor do multiplicador passado
            $multiplicador = MultiplicadorVaga::where('cota_curso_id', $cota_cursoA0->id)->first();
            if ($multiplicador != null) {
                $vagasCotaA0 *= $multiplicador->multiplicador;
            }

            //$candidatosCurso = $candidatosCurso->slice(0, $vagasCotaA0);

            $vagasCota = $this->fazerCadastro($A0, null, $curs, $candidatosCurso, $vagasCotaA0);

            $vagasCotaCollection = collect();
            $vagasCotaCollection->push(0);

            //Varremos todas as cotas do curso
            foreach ($curs->cotas()->where('sisu_id', $this->chamada->sisu->id)->get() as $cota) {
                if ($cota->cod_cota != $A0->cod_cota) {
                    //recuperamos informações da quantidade que iremos chamar
                    $cota_curso = $curs->cotas()->where('cota_id', $cota->id)->where('sisu_id', $this->chamada->sisu->id)->first()->pivot;

                    $vagasCota = $cota_curso->quantidade_vagas - $cota_curso->vagas_ocupadas;
                    //chamamos o número de vagas disponíveis vezes o valor do multiplicador passado
                    $multiplicador = MultiplicadorVaga::where([['cota_curso_id', $cota_curso->id], ['chamada_id', $this->chamada->id]])->first();
                    if (!is_null($multiplicador)) {
                        $vagasCota *= $multiplicador->multiplicador;
                    }

                    //aqui veremos se essa cota tem candidatos inscritos para fazer o cadastro
                    $cursoAtual = $cotasCursosCOD[$indexCurso];
                    $modalidadeDaCotaIndex = null;

                    //Se o curso atual possuir algum candidato da modalidade descrita na descricao da cota, significa que temos quem chamar
                    foreach ($cursoAtual as $index => $modalidadeCursoAtual) {
                        if ($modalidadeCursoAtual == $cota->descricao) {
                            $modalidadeDaCotaIndex = $index;
                            break;
                        }
                    }
                    //Então assim faremos
                    if (!is_null($modalidadeDaCotaIndex)) {
                        $vagasCota = $this->fazerCadastro($cota, $cota, $curs, $cursos[$indexCurso][$modalidadeDaCotaIndex], $vagasCota);
                    }
                    $vagasCotaCollection->push($vagasCota);
                }
            }
            //Varremos todas as cotas do curso
            foreach ($curs->cotas()->where('sisu_id', $this->chamada->sisu->id)->get() as $indice => $cota) {
                if ($cota->cod_cota != $A0->cod_cota) {
                    $vagasCota = $vagasCotaCollection[$indice];
                    //Caso restem vagas, faremos o remanejamento
                    if ($vagasCota > 0) {
                        foreach ($cota->remanejamentos as $remanejamento) {
                            $cotaRemanejamento = $remanejamento->proximaCota;
                            $cursoAtual = $cotasCursosCOD[$indexCurso];

                            $modalidadeDaCotaIndex = null;

                            foreach ($cursoAtual as $indexRemanejamento => $modalidadeCursoAtualRemanejamento) {
                                if ($modalidadeCursoAtualRemanejamento == $cotaRemanejamento->descricao) {
                                    $modalidadeDaCotaIndex = $indexRemanejamento;
                                    break;
                                }
                            }
                            if (!is_null($modalidadeDaCotaIndex)) {
                                $vagasCota = $this->fazerCadastro($cota, $cotaRemanejamento, $curs, $cursos[$indexCurso][$modalidadeDaCotaIndex], $vagasCota);
                            }
                            if ($vagasCota == 0) {
                                break;
                            }
                        }
                    }
                }
            }
        }
    }

    private function fazerCadastro($cota, $cotaRemanejamento, $curs, $porModalidade, $vagasCota)
    {
        //enquanto houver vagas e inscritos daquela modalidade, o laço irá continuar
        $ehNull = $cotaRemanejamento;
        foreach ($porModalidade as $inscrito) {
            if ($vagasCota > 0) {

                if ($ehNull == null) {
                    $cotaRemanejamento = $this->getCotaModalidade($inscrito['no_modalidade_concorrencia']);
                }
                //agora podemos preparar o objeto de inscricao para o candidato
                $inscricao = new Inscricao([
                    'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
                    'protocolo' => Hash::make($inscrito['protocolo'] . $this->chamada->id),
                    'nu_etapa' => $inscrito['nu_etapa'],
                    'no_campus' => $inscrito['no_campus'],
                    'co_ies_curso' => $inscrito['co_ies_curso'],
                    'no_curso' => $inscrito['no_curso'],
                    'ds_turno' => $inscrito['ds_turno'],
                    'ds_formacao' => $inscrito['ds_formacao'],
                    'qt_vagas_concorrencia' => $inscrito['qt_vagas_concorrencia'],
                    'co_inscricao_enem' => $inscrito['co_inscricao_enem'],
                    //'cd_efetivado' => false,
                    'no_social' => $inscrito['no_social'],
                    'tp_sexo' => $inscrito['tp_sexo'],
                    'nu_rg' => $inscrito['nu_rg'],
                    'no_mae' => $inscrito['no_mae'],
                    'ds_logradouro' => $inscrito['ds_logradouro'],
                    'nu_endereco' => $inscrito['nu_endereco'],
                    'ds_complemento' => $inscrito['ds_complemento'],
                    'sg_uf_inscrito' => $inscrito['sg_uf_inscrito'],
                    'no_municipio' => $inscrito['no_municipio'],
                    'no_bairro' => $inscrito['no_bairro'],
                    'nu_cep' => $inscrito['nu_cep'],
                    'nu_fone1' => $inscrito['nu_fone1'],
                    'nu_fone2' => $inscrito['nu_fone2'],
                    'ds_email' => $inscrito['ds_email'],
                    'nu_nota_l' => floatval($inscrito['nu_nota_l']),
                    'nu_nota_ch' => floatval($inscrito['nu_nota_ch']),
                    'nu_nota_cn' => floatval($inscrito['nu_nota_cn']),
                    'nu_nota_m' => floatval($inscrito['nu_nota_m']),
                    'nu_nota_r' => floatval($inscrito['nu_nota_r']),
                    'co_curso_inscricao' => $inscrito['co_curso_inscricao'],
                    'st_opcao' => $inscrito['st_opcao'],
                    'no_modalidade_concorrencia' => $inscrito['no_modalidade_concorrencia'],
                    'st_bonus_perc' => $inscrito['st_bonus_perc'],
                    'qt_bonus_perc' => $inscrito['qt_bonus_perc'],
                    'no_acao_afirmativa_bonus' => $inscrito['no_acao_afirmativa_bonus'],
                    'nu_nota_candidato' => floatval($inscrito['nu_nota_candidato']),
                    'nu_notacorte_concorrida' => floatval($inscrito['nu_notacorte_concorrida']),
                    'nu_classificacao' => intval($inscrito['nu_classificacao']),
                    'ds_matricula' => $inscrito['ds_matricula'],
                    'dt_operacao' => $inscrito['dt_operacao'],
                    'co_ies' => $inscrito['co_ies'],
                    'no_ies' => $inscrito['no_ies'],
                    'sg_ies' => $inscrito['sg_ies'],
                    'sg_uf_ies' => $inscrito['sg_uf_ies'],
                    'st_lei_optante' => $inscrito['st_lei_optante'],
                    'st_lei_renda' => $inscrito['st_lei_renda'],
                    'st_lei_etnia_p' => $inscrito['st_lei_etnia_p'],
                    'st_lei_etnia_i' => $inscrito['st_lei_etnia_i'],
                    'de_acordo_lei_cota' => $inscrito['de_acordo_lei_cota'],
                    'ensino_medio' => $inscrito['ensino_medio'],
                    'quilombola' => $inscrito['quilombola'],
                    'deficiente' => $inscrito['deficiente'],
                    'modalidade_escolhida' => $inscrito['modalidade_escolhida'],
                    'tipo_concorrencia' => $inscrito['tipo_concorrencia'],
                ]);

                //recuperamos se o inscrito possui um usuário no sistema
                $candidatoExistente = Candidato::where('nu_cpf_inscrito', $inscrito['nu_cpf_inscrito'])->first();
                if ($candidatoExistente == null) {
                    //caso não exista, criaremos um para ele
                    $user = new User([
                        'name' => $inscrito['no_inscrito'],
                        'password' => Hash::make('12345678'),
                        'role' => User::ROLE_ENUM['candidato'],
                        'primeiro_acesso' => true,
                    ]);
                    //aqui estamos usando o nome social dele caso o mesmo possua
                    if ($inscrito['no_social'] != null) {
                        $user->name = $inscrito['no_social'];
                    }
                    $user->save();

                    if ($inscrito['no_social'] != null) {
                        $candidato = new Candidato([
                            'no_inscrito' => $inscrito['no_inscrito'],
                            'no_social' => $inscrito['no_social'],
                            'nu_cpf_inscrito' => $inscrito['nu_cpf_inscrito'],
                            'dt_nascimento' => $inscrito['dt_nascimento'],
                        ]);
                    } else {
                        $candidato = new Candidato([
                            'no_inscrito' => $inscrito['no_inscrito'],
                            'nu_cpf_inscrito' => $inscrito['nu_cpf_inscrito'],
                            'dt_nascimento' => $inscrito['dt_nascimento'],
                        ]);
                    }
                    $candidato->etnia_e_cor = strval(array_search($inscrito['etnia_e_cor'], Candidato::ETNIA_E_COR));

                    $candidato->user_id = $user->id;
                    $candidato->save();

                    $inscricao->chamada_id = $this->chamada->id;
                    $inscricao->sisu_id = $this->chamada->sisu->id;
                    $inscricao->candidato_id = $candidato->id;

                    $inscricao->cota_id = $cotaRemanejamento->id;
                    $inscricao->cota_vaga_ocupada_id = $cota->id;

                    $inscricao->curso_id = $curs->id;
                    $inscricao->save();
                } else {
                    //Caso o inscrito já possua cadastro no sistema, checamos se ele já foi chamado naquela edição do sisu
                    //isso evita que ele seja chamado novamente naquela edição, e permite que o mesmo arquivo csv
                    //da lista de espera seja utilizado em outras chamadas.
                    $chamado = False;
                    foreach ($candidatoExistente->inscricoes as $inscricaoCandidato) {
                        if ($inscricaoCandidato->chamada->sisu->id == $this->chamada->sisu->id) {
                            $chamado = True;
                            break;
                        }
                    }
                    if (!$chamado) {
                        $candidatoExistente->atualizar_dados = true;
                        if ($inscrito['no_social'] != null) {
                            $candidatoExistente->no_social = $inscrito['no_social'];
                            $candidatoExistente->user->name = $inscrito['no_social'];
                        } else {
                            $candidatoExistente->user->name = $inscrito['no_inscrito'];
                        }
                        $candidatoExistente->etnia_e_cor = strval(array_search($inscrito['etnia_e_cor'], Candidato::ETNIA_E_COR));

                        $candidatoExistente->update();
                        $candidatoExistente->user->update();

                        $inscricao->chamada_id = $this->chamada->id;
                        $inscricao->sisu_id = $this->chamada->sisu->id;
                        $inscricao->candidato_id = $candidatoExistente->id;

                        $inscricao->cota_id = $cotaRemanejamento->id;
                        $inscricao->cota_vaga_ocupada_id = $cota->id;

                        $inscricao->curso_id = $curs->id;
                        $inscricao->save();
                    } else {
                        $vagasCota += 1;
                    }
                }
                $vagasCota -= 1;
            } else {
                break;
            }
        }

        return $vagasCota;
    }

    private function getCotaModalidade($modalidade)
    {
        if (
            $modalidade == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.'
            || $modalidade == 'AMPLA CONCORRÊNCIA' || $modalidade == 'Ampla concorrência'
        ) {
            return Cota::where('cod_cota', 'A0')->first();
        }

        return Cota::where('nome', $modalidade)->first();
    }
}
