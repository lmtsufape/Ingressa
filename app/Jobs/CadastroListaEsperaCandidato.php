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
        ini_set('max_execution_time', 900);
        ini_set('auto_detect_line_endings', true);
        $dados = fopen(storage_path('app'.DIRECTORY_SEPARATOR.$this->chamada->sisu->caminho_import_espera), "r");
        $primeira = true;
        $candidatos = collect();
        $cont = 0;
        while ( ($data = fgetcsv($dados,";",';') ) !== FALSE ) {
            /*if($cont > 5){
                break;
            }*/
            if($primeira){
                $primeira = false;
            }else{
                //Armazenamos as informações de cada candidato
                $inscricao = array(
                    'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
                    'protocolo' => Hash::make(strval($data[8]).$this->chamada->id),
                    'nu_etapa' => strval($data[0]),
                    'no_campus' => strval($data[1]),
                    'co_ies_curso' => strval($data[2]),
                    'no_curso' => strval($data[3]),
                    'ds_turno' => strval($data[4]),
                    'ds_formacao' => strval($data[5]),
                    'qt_vagas_concorrencia' => strval($data[6]),
                    'co_inscricao_enem' => strval($data[7]),

                    'no_inscrito' => strval($data[8]),
                    'no_social' => strval($data[9]),
                    'nu_cpf_inscrito' => strval($data[10]),
                    'dt_nascimento' => DateTime::createFromFormat('Y-m-d H:i:s', $data[11])->format('Y-m-d'),

                    //'cd_efetivado' => false,
                    'tp_sexo' => strval($data[12]),
                    'nu_rg' => strval($data[13]),
                    'no_mae' => strval($data[14]),
                    'ds_logradouro' => strval($data[15]),
                    'nu_endereco' => strval($data[16]),
                    'ds_complemento' => strval($data[17]),
                    'sg_uf_inscrito' => strval($data[18]),
                    'no_municipio' => strval($data[19]),
                    'no_bairro' => strval($data[20]),
                    'nu_cep' => strval($data[21]),
                    'nu_fone1' => strval($data[22]),
                    'nu_fone2' => strval($data[23]),
                    'ds_email' => strval($data[24]),
                    'nu_nota_l' => floatval(str_replace( ',', '.', $data[25])),
                    'nu_nota_ch' => floatval(str_replace( ',', '.', $data[26])),
                    'nu_nota_cn' => floatval(str_replace( ',', '.', $data[27])),
                    'nu_nota_m' => floatval(str_replace( ',', '.', $data[28])),
                    'nu_nota_r' => floatval(str_replace( ',', '.', $data[29])),
                    'co_curso_inscricao' => strval($data[30]),
                    'st_opcao' => strval($data[31]),
                    'no_modalidade_concorrencia' => strval($data[32]),
                    'st_bonus_perc' => strval($data[33]),
                    'qt_bonus_perc' => strval($data[34]),
                    'no_acao_afirmativa_bonus' => strval($data[35]),
                    'nu_nota_candidato' => floatval(str_replace( ',', '.', $data[36])),
                    'nu_notacorte_concorrida' => floatval(str_replace( ',', '.', $data[37])),
                    'nu_classificacao' => intval($data[38]),
                    'ds_matricula' => strval($data[39]),
                    'dt_operacao' => DateTime::createFromFormat('Y-m-d H:i:s', $data[40])->format('Y/m/d'),
                    'co_ies' => strval($data[41]),
                    'no_ies' => strval($data[42]),
                    'sg_ies' => strval($data[43]),
                    'sg_uf_ies' => strval($data[44]),
                    'st_lei_optante' => strval($data[45]),
                    'st_lei_renda' => strval($data[46]),
                    'st_lei_etnia_p' => strval($data[47]),
                    'st_lei_etnia_i' => strval($data[48]),
                );
                $candidatos->push($inscricao);
                $cont += 1;
            }
        }


        //Agrupamos por curso
        $grouped = $candidatos->groupBy(function ($candidato) {
            return $candidato['co_ies_curso'].$candidato['ds_turno'];
        });
        $porCurso = collect();
        //E separamos por modalidade
        foreach($grouped as $curso){
            $porCurso->push($curso->groupBy('no_modalidade_concorrencia'));
        }

        $cursos = collect();

        //Collection para armazenar apenas as modalidades de cada curso que estão presentes no arquivo, para sabermos se há candidatos a
        //serem chamados daquela modalidade
        $cotasCursosCOD = collect();

        //Feito isto, é necessário juntar todos os inscritos da ampla concorrencia independente da cota de 10%
        foreach($porCurso as $curso){
            $modalidade = collect();
            $ampla = collect();
            $modalidades = collect();

            $cotaCOD = collect();
            $cotaAmpla = false;
            foreach($curso as $porModalidade){
                //os de ampla são colocados em um único collection aqui. Há várias verificações por inconsistência dos dados fornecidos
                if($porModalidade[0]['no_modalidade_concorrencia'] == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
                    $porModalidade[0]['no_modalidade_concorrencia'] == 'AMPLA CONCORRÊNCIA' || $porModalidade[0]['no_modalidade_concorrencia'] == 'Ampla concorrência'){
                    $ampla = $ampla->concat($porModalidade);
                    if(!$cotaAmpla){
                        $cotaAmpla = true;
                    }
                }else{
                    $modalidade = $porModalidade;
                    if(!$cotaCOD->contains($modalidade[0]['no_modalidade_concorrencia'])){
                        $cotaCOD->push($modalidade[0]['no_modalidade_concorrencia']);
                    }
                    $modalidade = $modalidade->sortBy(function($candidato){
                        return $candidato['nu_classificacao'];
                    });
                    $modalidades->push($modalidade);
                }
            }
            //ordenamos os inscritos da modalidade daquele curso pela classificacao
            $ampla = $ampla->sortBy(function($candidato){
                return $candidato['nu_classificacao'];
            });
            $modalidades->push($ampla);
            $cursos->push($modalidades);
            if($cotaAmpla){
                $cotaCOD->push(Cota::COD_COTA_ENUM['A0']);
            }
            $cotasCursosCOD->push($cotaCOD);
        }

        //Preparados os dados dos inscritos, agora criaremos as instancias para salvar no banco
        //Percorremos cada curso
        foreach($cursos as $indexCurso => $curso){
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

            if($candidato['ds_turno'] == 'Matutino'){
                $turno =  Curso::TURNO_ENUM['matutino'];
            }elseif($candidato['ds_turno'] == 'Vespertino'){
                $turno = Curso::TURNO_ENUM['vespertino'];
            }elseif($candidato['ds_turno'] == 'Noturno'){
                $turno = Curso::TURNO_ENUM['noturno'];
            }elseif($candidato['ds_turno'] == 'Integral'){
                $turno = Curso::TURNO_ENUM['integral'];
            }

            //E recuperamos a instancia do curso do banco de dados
            $curs = Curso::where([['cod_curso', $candidato['co_ies_curso']], ['turno', $turno]])->first();

            /*Para a nova regra de chamadas da lista de espera, e necessario preencher o restante de vagas da ampla concorrencia
            com os candidatos com as maiores notas  daquele curso*/

            $candidatosCurso = collect();
            foreach($cursos[$indexCurso] as $modalidadeAtual){
                $candidatosCurso = $candidatosCurso->concat($modalidadeAtual->all());
            }

            $candidatosCurso = $candidatosCurso->sortByDesc(function($candidato){
                return $candidato['nu_nota_candidato'];
            });

            $A0 = Cota::where('cod_cota', 'A0')->first();
            $cota_cursoA0 = $curs->cotas()->where('cota_id', $A0->id)->first()->pivot;
            $vagasCotaA0 = $cota_cursoA0->quantidade_vagas - $cota_cursoA0->vagas_ocupadas;
            
            //chamamos o número de vagas disponíveis vezes o valor do multiplicador passado
            $multiplicador = MultiplicadorVaga::where('cota_curso_id', $cota_cursoA0->id)->first();
            if($multiplicador != null){
                $vagasCotaA0 *= $multiplicador->multiplicador;
            }

            //$candidatosCurso = $candidatosCurso->slice(0, $vagasCotaA0);

            $vagasCota = $this->fazerCadastro($A0, null, $curs, $candidatosCurso, $vagasCotaA0);

            //Varremos todas as cotas do curso
            foreach($curs->cotas as $cota){
                if($cota->cod_cota != $A0->cod_cota){
                    //recuperamos informações da quantidade que iremos chamar
                    $cota_curso = $curs->cotas()->where('cota_id', $cota->id)->first()->pivot;

                    $vagasCota = $cota_curso->quantidade_vagas - $cota_curso->vagas_ocupadas;
                    //chamamos o número de vagas disponíveis vezes o valor do multiplicador passado
                    $multiplicador = MultiplicadorVaga::where([['cota_curso_id', $cota_curso->id], ['chamada_id', $this->chamada->id]])->first();
                    if(!is_null($multiplicador)){
                        $vagasCota *= $multiplicador->multiplicador;
                    }

                    //aqui veremos se essa cota tem candidatos inscritos para fazer o cadastro
                    $cursoAtual = $cotasCursosCOD[$indexCurso];
                    $modalidadeDaCotaIndex = null;

                    //Se o curso atual possuir algum candidato da modalidade descrita na descricao da cota, significa que temos quem chamar
                    foreach($cursoAtual as $index => $modalidadeCursoAtual){
                        if($modalidadeCursoAtual == $cota->descricao){
                            $modalidadeDaCotaIndex = $index;
                            break;
                        }
                    }
                    //Então assim faremos
                    if(!is_null($modalidadeDaCotaIndex)){
                        $vagasCota = $this->fazerCadastro($cota, $cota, $curs, $cursos[$indexCurso][$modalidadeDaCotaIndex], $vagasCota);
                    }

                    //Caso restem vagas, faremos o remanejamento
                    if($vagasCota > 0){
                        foreach($cota->remanejamentos as $remanejamento){
                            $cotaRemanejamento = $remanejamento->proximaCota;
                            $cursoAtual = $cotasCursosCOD[$indexCurso];

                            $modalidadeDaCotaIndex = null;

                            foreach($cursoAtual as $indexRemanejamento => $modalidadeCursoAtualRemanejamento){
                                if($modalidadeCursoAtualRemanejamento == $cotaRemanejamento->descricao){
                                    $modalidadeDaCotaIndex = $indexRemanejamento;
                                    break;
                                }
                            }
                            if(!is_null($modalidadeDaCotaIndex)){
                                $vagasCota = $this->fazerCadastro($cota, $cotaRemanejamento, $curs, $cursos[$indexCurso][$modalidadeDaCotaIndex], $vagasCota);
                            }
                            if($vagasCota == 0){
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
        foreach($porModalidade as $inscrito){
            if($vagasCota > 0){

                if($ehNull == null){
                    $cotaRemanejamento = $this->getCotaModalidade($inscrito['no_modalidade_concorrencia']);
                }
                //agora podemos preparar o objeto de inscricao para o candidato
                $inscricao = new Inscricao([
                    'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
                    'protocolo' => Hash::make($inscrito['protocolo'].$this->chamada->id),
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
                ]);

                //recuperamos se o inscrito possui um usuário no sistema
                $candidatoExistente = Candidato::where('nu_cpf_inscrito', $inscrito['nu_cpf_inscrito'])->first();
                if($candidatoExistente == null){
                    //caso não exista, criaremos um para ele
                    $user = new User([
                        'name' => $inscrito['no_inscrito'],
                        'password' => Hash::make('12345678'),
                        'role' => User::ROLE_ENUM['candidato'],
                        'primeiro_acesso' => true,
                    ]);
                    //aqui estamos usando o nome social dele caso o mesmo possua
                    if($inscrito['no_social'] != null){
                        $user->name = $inscrito['no_social'];
                    }
                    $user->save();

                    if($inscrito['no_social'] != null){
                        $candidato = new Candidato([
                            'no_inscrito' => $inscrito['no_inscrito'],
                            'no_social' => $inscrito['no_social'],
                            'nu_cpf_inscrito' => $inscrito['nu_cpf_inscrito'],
                            'dt_nascimento' => $inscrito['dt_nascimento'],
                        ]);
                    }else{
                        $candidato = new Candidato([
                            'no_inscrito' => $inscrito['no_inscrito'],
                            'nu_cpf_inscrito' => $inscrito['nu_cpf_inscrito'],
                            'dt_nascimento' => $inscrito['dt_nascimento'],
                        ]);
                    }

                    $candidato->user_id = $user->id;
                    $candidato->save();

                    $inscricao->chamada_id = $this->chamada->id;
                    $inscricao->sisu_id = $this->chamada->sisu->id;
                    $inscricao->candidato_id = $candidato->id;

                    $inscricao->cota_id = $cotaRemanejamento->id;
                    $inscricao->cota_vaga_ocupada_id = $cota->id;

                    $inscricao->curso_id = $curs->id;
                    $inscricao->save();

                }else{
                    //Caso o inscrito já possua cadastro no sistema, checamos se ele já foi chamado naquela edição do sisu
                    //isso evita que ele seja chamado novamente naquela edição, e permite que o mesmo arquivo csv
                    //da lista de espera seja utilizado em outras chamadas.
                    $chamado = False;
                    foreach($candidatoExistente->inscricoes as $inscricaoCandidato){
                        if($inscricaoCandidato->chamada->sisu->id == $this->chamada->sisu->id){
                            $chamado = True;
                            break;
                        }
                    }
                    if(!$chamado){
                        $candidatoExistente->atualizar_dados = true;
                        if($inscrito['no_social'] != null){
                            $candidatoExistente->no_social = $inscrito['no_social'];
                            $candidatoExistente->user->name = $inscrito['no_social'];
                        }else{
                            $candidatoExistente->user->name = $inscrito['no_inscrito'];
                        }
                        $candidatoExistente->update();
                        $candidatoExistente->user->update();

                        $inscricao->chamada_id = $this->chamada->id;
                        $inscricao->sisu_id = $this->chamada->sisu->id;
                        $inscricao->candidato_id = $candidatoExistente->id;

                        $inscricao->cota_id = $cotaRemanejamento->id;
                        $inscricao->cota_vaga_ocupada_id = $cota->id;

                        $inscricao->curso_id = $curs->id;
                        $inscricao->save();
                    }else{
                        $vagasCota += 1;
                    }

                }
                $vagasCota -= 1;
            }else{
                break;
            }
        }

        return $vagasCota;

    }

    private function getCotaModalidade($modalidade)
    {
        switch($modalidade){
            case 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.':
                return Cota::where('cod_cota', 'A0')->first();
            case 'AMPLA CONCORRÊNCIA':
                return Cota::where('cod_cota', 'A0')->first();
            case 'Ampla concorrência':
                return Cota::where('cod_cota', 'A0')->first();
            case 'Candidatos com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).':
                return Cota::where('cod_cota', 'L1')->first();
            case 'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).':
                return Cota::where('cod_cota', 'L2')->first();
            case 'Candidatos que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).':
                return Cota::where('cod_cota', 'L5')->first();
            case 'Candidatos autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).':
                return Cota::where('cod_cota', 'L6')->first();
            case 'Candidatos com deficiência que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).':
                return Cota::where('cod_cota', 'L9')->first();
            case 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas, que tenham renda familiar bruta per capita igual ou inferior a 1,5 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)':
                return Cota::where('cod_cota', 'L10')->first();
            case 'Candidatos com deficiência que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).':
                return Cota::where('cod_cota', 'L13')->first();
            case 'Candidatos com deficiência autodeclarados pretos, pardos ou indígenas que, independentemente da renda (art. 14, II, Portaria Normativa nº 18/2012), tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).':
                return Cota::where('cod_cota', 'L14')->first();
        }
    }
}
