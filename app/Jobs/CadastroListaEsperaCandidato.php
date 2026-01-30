<?php

namespace App\Jobs;

use App\Models\Chamada;
use App\Models\Candidato;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\MultiplicadorVaga;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;


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
        $csv = Reader::from($csvPath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        // Arrays para armazenar os dados dos usuários, candidatos e inscrições
        $usersData = [];
        $candidatosData = [];
        $inscricoesData = [];

        // Otimização para pegar apenas os candidatos que já estão cadastrados e usar indexação para tornar a busca mais rápida
        $cpfInscritos = array_column(iterator_to_array($records), 'NU_CPF_INSCRITO');
        $candidatos = Candidato::whereIn('nu_cpf_inscrito', $cpfInscritos)
            ->with('user')
            ->get()
            ->keyBy('nu_cpf_inscrito');


        // Pega o próximo valor da sequência para que seja possível inserir os ids sem usar o método create ou save dentro do foreach
        $nextUserIdValue = DB::select("SELECT nextval('users_id_seq')")[0]->nextval;
        $nextCandidatoIdValue = DB::select("SELECT nextval('candidatos_id_seq')")[0]->nextval;

        foreach ($records as $record) {
            $candidato = $candidatos->get($record['NU_CPF_INSCRITO']);

            // Cria um novo candidato e usuário caso ele não exista
            if (!$candidato) {
                if (!in_array($record['NO_INSCRITO'], array_column($candidatosData, 'no_inscrito'))) {

                // Adiciona o usuário no array para inserção
                $usersData[] = [
                    'id' =>  $nextUserIdValue,
                    'name' => empty($record['NO_SOCIAL']) ? $record['NO_INSCRITO'] : $record['NO_SOCIAL'],
                    'password' => '', // A senha será modificada quando o usuário acessar a conta pela primeira vez
                    'role' => User::ROLE_ENUM['candidato'],
                    'primeiro_acesso' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // Adiciona o candidato no array para inserção
                $candidatosData[] = [
                    'id' => $nextCandidatoIdValue++,
                    'no_social' => $record['NO_SOCIAL'],
                    'no_inscrito' => $record['NO_INSCRITO'],
                    'nu_cpf_inscrito' => $record['NU_CPF_INSCRITO'],
                    'dt_nascimento' => Carbon::createFromFormat('d/m/Y', $record['DT_NASCIMENTO'])->format('Y-m-d'),
                    'etnia_e_cor' => Candidato::ETNIA_E_COR[$record['COR_RACA']],
                    'user_id' => $nextUserIdValue++,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'atualizar_dados' => true,
                ];
            }

                // Atualiza dados do candidato caso ele exista
            } else {
                if (!in_array($candidato->id, array_column($candidatosData, 'id'))) {
                $candidatosData[] = [
                    'id' => $candidato->id,
                    'atualizar_dados' => true,
                    'no_social' => $record['NO_SOCIAL'],
                    'updated_at' => now(),

                    // Os campos abaixo não serão atualizados, mas precisam ser passados para o método upsert por conta do funcionamento interno do postgres
                    'user_id' => 0,
                    'no_inscrito' => '',
                    'nu_cpf_inscrito' => '',
                    'dt_nascimento' => now(),
                    'etnia_e_cor' => 0,
                    'created_at' => now(),
                ];



                $usersData[] = [
                    'id' => $candidato->user->id,
                    'name' => empty($record['NO_SOCIAL']) ? $record['NO_INSCRITO'] : $record['NO_SOCIAL'],
                    'updated_at' => now(),

                    // Os campos abaixo não serão atualizados, mas precisam ser passados para o método upsert por conta do funcionamento interno do postgres
                    'password' => '',
                    'role' => 0,
                    'primeiro_acesso' => true,
                ];
            }
            }

            $cpfC = -1;
            if( $candidato) {
                $cpfC = $candidato->id;
            }
            else {
                $index = array_search($record['NU_CPF_INSCRITO'], array_column($candidatosData, 'nu_cpf_inscrito'));
                if($index !== false) {
                    $cpfC = $candidatosData[$index]['id'];
                }
            }

            // Adicionando inscrição apenas se o candidato não existir ou se ele não estiver inscrito nesse SiSU
            if (!$candidato || !$candidato->inscricoes()->where('sisu_id', $this->chamada->sisu->id)->exists()) {
                $inscricoesData[] = [
                    'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
                    'protocolo' => '',
                    'nu_etapa' => $record['NU_ETAPA'],
                    'no_campus' => $record['NO_CAMPUS'],
                    'co_ies_curso' => $record['CO_IES_CURSO'],
                    'no_curso' => $record['NO_CURSO'],
                    'ds_turno' => $record['DS_TURNO'],
                    'ds_formacao' => $record['DS_FORMACAO'],
                    'qt_vagas_concorrencia' => $record['QT_VAGAS_CONCORRENCIA'],
                    'co_inscricao_enem' => $record['CO_INSCRICAO_ENEM'],
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
                    'qt_bonus_perc' => $record['QT_BONUS_PERCENTUAL'],
                    'no_acao_afirmativa_bonus' => $record['NO_ACAO_AFIRMATIVA_BONUS'],
                    'nu_nota_candidato' => str_replace(',', '.', $record['NU_NOTA_CANDIDATO']),
                    'nu_notacorte_concorrida' => str_replace(',', '.', $record['NU_NOTACORTE_CONCORRIDA']),
                    'nu_classificacao' => $record['NU_CLASSIFICACAO'],
                    'ds_matricula' => $record['DS_MATRICULA'],
                    'dt_operacao' => !empty($record['DT_OPERACAO']) ? Carbon::createFromFormat('Y-m-d H:i:s', $record['DT_OPERACAO'])->format('Y-m-d H:i:s') : null,
                    'co_ies' => $record['CO_IES'],
                    'no_ies' => $record['NO_IES'],
                    'sg_ies' => $record['SG_IES'],
                    'sg_uf_ies' => $record['SG_UF_IES'],
                    'ensino_medio' => $record['ENSINO_MEDIO'],
                    'quilombola' => $record['QUILOMBOLA'],
                    'deficiente' => $record['PcD'],
                    'media_simples_pdm_licenca' => floatval(str_replace(',', '.', $record['MEDIA_SIMPLES_PDM_LICENC'])),
                    'st_baixa_renda' => $record['ST_BAIXA_RENDA'],
                    'st_rank_baixa_renda' => $record['ST_RANK_BAIXA_RENDA'],
                    'st_adesao_acao_afirmativa_curso' => $record['ST_ADESAO_ACAO_AFIRMATIVA_CURS'],
                    'st_rank_ensino_medio' => $record['ST_RANK_ENSINO_MEDIO'],
                    'st_rank_raca' => $record['ST_RANK_RACA'],
                    'st_rank_quilombola' => $record['ST_RANK_QUILOMBOLA'],
                    'st_rank_pcd' => $record['ST_RANK_PcD'],
                    'st_confirma_lgpd' => $record['ST_CONFIRMA_LGPD'],
                    // 'total_membros_familiar' => intval($record['TOTAL_MEMBROS_FAMILIAR']),
                    // 'renda_familiar_bruta' => floatval(str_replace(',', '.', $record['RENDA_FAMILIAR_BRUTA'])),
                    'salario_minimo' => floatval(str_replace(',', '.', $record['SALARIO_MINIMO'])),
                    'perfil_economico_lei_cotas' => $record['PERFIL_ECONOMICO_LEI_COTAS'],
                    'tipo_concorrencia' => trim($record['TIPO_CONCORRENCIA']),
                    'no_acao_afirmativa_propria_ies' => $record['NO_ACAO_AFIRMATIVA_PROPRIA_IES'],
                    'chamada_id' => $this->chamada->id,
                    'sisu_id' => $this->chamada->sisu->id,
                    'cota_id' => Cota::getCotaModalidade($record['NO_MODALIDADE_CONCORRENCIA'])->id,
                    'candidato_id' => $cpfC, //$candidato ? $candidato->id :  $nextCandidatoIdValue++,
                    'curso_id' => Curso::where('cod_curso', $record['CO_IES_CURSO'])
                        ->where('turno', Curso::TURNO_ENUM[$record['DS_TURNO']])
                        ->first()
                        ->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $ordemModalidades = [
            'AC'     => 1,
            'LI_EP'  => 2,
            'LI_PCD' => 3,
            'LI_Q'   => 4,
            'LI_PPI' => 5,
            'LB_EP'  => 6,
            'LB_PCD' => 7,
            'LB_Q'   => 8,
            'LB_PPI' => 9
        ];

        // Agrupa inscrições por curso, turno e modalidade de concorrência juntando ampla concorrência com bônus e sem bônus em uma única modalidade.
        // Após isso, ordena as modalidades de acordo com $ordemModalidades e os inscritos por nota decrescente
        $inscricoesOrdenadas = collect($inscricoesData)
            ->groupBy([
                'co_ies_curso',
                'ds_turno',
                function ($item) {
                    // Junta a ampla concorrência com bônus e sem bônus em uma única modalidade e agrupa usando o código da cota
                    return Cota::getCotaModalidade($item['no_modalidade_concorrencia'])->cod_novo;
                }
            ])->map(function ($cursos) use ($ordemModalidades) {
                // Ordenar modalidades de acordo com $ordemModalidades
                return $cursos->map(function ($turnos) use ($ordemModalidades) {
                    $ordenadoPorModalidade = $turnos->sortKeysUsing(function ($key1, $key2) use ($ordemModalidades) {
                        $ordem1 = $ordemModalidades[$key1] ?? PHP_INT_MAX;
                        $ordem2 = $ordemModalidades[$key2] ?? PHP_INT_MAX;
                        return $ordem1 <=> $ordem2;
                    });

                    // Ordenar inscritos dentro de cada modalidade por nota decrescente
                    return $ordenadoPorModalidade->map(function ($inscritos) {
                        return collect($inscritos)->sortByDesc('nu_nota_candidato')->values();
                    });
                });
            });

        // Arrays para armazenar os candidatos convocados e reservas
        $candidatosConvocados = [];
        $candidatosReservas = [];


        // Os candidatos estão agrupados por curso, turno e modalidade de concorrência. O primeiro foreach itera pelos curso, o segundo pelo turno e o terceiro pela modalidade e o quarto pelos candidatos.
        foreach ($inscricoesOrdenadas as $codCurso => $curso) {
            foreach ($curso as $nomeTurno => $turno) {
                $vagasModalidade = []; // Armazena a quantidade de vagas restantes para cada modalidade
                $cloneVagas = [];

                // Processa os candidatos até preencher todas as vagas reais de todas as modalidades
                foreach ($turno as $codCota => $modalidade) {

                    // Acessa a tabela intermediária
                    $cotaCurso = Cota::firstWhere('cod_novo', $codCota)
                        ->cursos()
                        ->where('cod_curso', $codCurso)
                        ->where('turno', Curso::TURNO_ENUM[$nomeTurno])
                        ->wherePivot('sisu_id', $this->chamada->sisu->id)
                        ->first()
                        ->pivot;

                    // Recupera o multiplicador da modalidade
                    $multiplicador = MultiplicadorVaga::where([
                        ['cota_curso_id', $cotaCurso->id],
                        ['chamada_id', $this->chamada->id]
                    ])->first();

                    // Calcula quantidade de vagas reais e reservas e armazena na chave correspondente no código da cota
                    $multiplicador = $multiplicador ? $multiplicador->multiplicador : 1;
                    $vagasModalidade[$codCota]['reais'] = $cotaCurso->quantidade_vagas - $cotaCurso->vagas_ocupadas;
                    $vagasModalidade[$codCota]['reservas'] = $vagasModalidade[$codCota]['reais'] * ($multiplicador - 1); // O multiplicador é subtraído por 1 pois as vagas reais já foram contabilizadas


                    $cloneVagas[$codCota]['reais'] = $cotaCurso->quantidade_vagas - $cotaCurso->vagas_ocupadas;

                    foreach ($modalidade as $candidato) {
                        if ($vagasModalidade[$codCota]['reais'] > 0) { // Candidatos que possuem vaga garantida
                            $convocado = false;

                            // Verifica se o candidato já foi convocado
                            foreach ($candidatosConvocados as $candidatoConvocado) {
                                if ($candidato['ds_email'] === $candidatoConvocado['ds_email']) {
                                    $convocado = true;
                                    break;
                                }
                            }

                            if ($convocado) {
                                continue;
                            } else { // Adiciona o candidato à lista de convocados
                                $candidato['cota_vaga_ocupada_id'] = Cota::firstWhere('cod_novo', $codCota)->id;
                                $candidatosConvocados[] = $candidato;
                                $vagasModalidade[$codCota]['reais']--;
                            }
                        } else break;
                    }
                }

                // Remanejamento
                foreach ($vagasModalidade as $codCota => $vagas) {
                    if ($vagas['reais'] > 0) {
                        $cota = Cota::firstWhere('cod_novo', $codCota);
                        $remanejamentos = $cota->remanejamentos;

                        foreach ($remanejamentos as $remanejamento) {
                            $preenchido = false;

                            foreach ($turno->get($remanejamento->proximaCota->cod_novo) ?? [] as $candidato) {
                                if ($vagasModalidade[$codCota]['reais'] > 0) {
                                    $convocado = false;

                                    // Verifica se o candidato já foi convocado
                                    foreach ($candidatosConvocados as $candidatoConvocado) {
                                        if ($candidato['ds_email'] === $candidatoConvocado['ds_email']) {
                                            $convocado = true;
                                            break;
                                        }
                                    }

                                    if ($convocado) {
                                        continue;
                                    } else { // Remaneja o candidato
                                        $candidato['cota_vaga_ocupada_id'] = $cota->id;
                                        $candidatosConvocados[] = $candidato;
                                        $vagasModalidade[$codCota]['reais']--;
                                    }
                                } else {
                                    $preechido = true;
                                    break;
                                }
                            }

                            if ($preenchido) break;
                        }
                    }
                }


                // Desagrupa e ordena os candidatos pela maior nota, em seguida pelos candidatos com mais mais chances de serem convocados e por fim pela modalidade menos restritiva para a mais restritiva
                /*$candidatosDesagrupados = $turno->flatmap(function ($modalidade) {
                    return $modalidade;
                })->sortByDesc([
                    'nu_nota_candidato',
                    function ($item) use ($vagasModalidade) {
                        $codCota = Cota::getCotaModalidade($item['no_modalidade_concorrencia'])->cod_novo;
                        return $vagasModalidade[$codCota]['reservas'] - $item['nu_classificacao'];
                    },
                    function ($item) use ($ordemModalidades) {
                        return -$ordemModalidades[Cota::getCotaModalidade($item['no_modalidade_concorrencia'])->cod_novo];
                    }
                ]);*/


                $candidatosDesagrupados = $turno->flatMap(function ($modalidade) {
                    return $modalidade;
                })->sort(function ($a, $b) use ($vagasModalidade, $ordemModalidades, $cloneVagas) {

                    $codCotaA = Cota::getCotaModalidade($a['no_modalidade_concorrencia'])->cod_novo;
                    $codCotaB = Cota::getCotaModalidade($b['no_modalidade_concorrencia'])->cod_novo;

                    $notaA = $a['nu_nota_candidato'];
                    $notaB = $b['nu_nota_candidato'];

                //    $classificacaoA = $a['nu_classificacao'] - $vagasModalidade[$codCotaA]['reservas'];
                //    $classificacaoB = $b['nu_classificacao'] - $vagasModalidade[$codCotaB]['reservas'];

                 $classificacaoA = ($cloneVagas[$codCotaA]['reais'] == 0)? 1000: ($a['nu_classificacao'] - $vagasModalidade[$codCotaA]['reservas']) / $cloneVagas[$codCotaA]['reais'] ;
                 $classificacaoB = ($cloneVagas[$codCotaB]['reais'] == 0)? 1000: ($b['nu_classificacao'] - $vagasModalidade[$codCotaB]['reservas']) / $cloneVagas[$codCotaB]['reais'];

                    $ordemA = $ordemModalidades[$codCotaA];
                    $ordemB = $ordemModalidades[$codCotaB];

                /* Favor não apagar que isso ajuda a debugar se houver erro em um usuário específico

       if(strcasecmp(trim($a['ds_email']), trim("mariathainaragomesdemelo@gmail.com")) === 0 && strcasecmp(trim($b['ds_email']), trim("mariathainaragomesdemelo@gmail.com")) === 0 ) {
$string = $a['tipo_concorrencia'] . ' ' . $cloneVagas[$codCotaA]['reais'] . ", ". $a['nu_classificacao'] . "-" . $classificacaoA . " vs " . $b['tipo_concorrencia'] . ' ' . $cloneVagas[$codCotaB]['reais'] . ", ". $b['nu_classificacao']  . "-" . $classificacaoB ;// . " : " . $classificacaoB <=> $classificacaoA . "\n";
                       var_dump($string);
                       var_dump($classificacaoA <=> $classificacaoB);

                    }*/

                    // Comparação decrescente: primeiro por nota, depois por classificação, depois por ordem da modalidade
                    return $notaB <=> $notaA ?: $classificacaoA <=> $classificacaoB ?: $ordemB <=> $ordemA;
                });



                $nota_paulo = [];
                $vaga_modalidade = [];
                $vaga = [];
                // Processa os candidatos até preencher todas as vagas reserva de todas as modalidades
                foreach ($candidatosDesagrupados as $candidato) {

               /*     if(strcasecmp(trim($candidato['ds_email']), trim("mariathainaragomesdemelo@gmail.com")) === 0 ) {
                        $codCota = Cota::getCotaModalidade($candidato['no_modalidade_concorrencia'])->cod_novo;
                        $vg =  $vagasModalidade[$codCota]['reservas'] - $candidato['nu_classificacao'];
                        $nota_paulo[] = $candidato;
                        $vaga_modalidade[] =  $vg ;
                        $vaga[] = $vagasModalidade[$codCota]['reservas'];
                    }*/

                    $codCota = Cota::getCotaModalidade($candidato['no_modalidade_concorrencia'])->cod_novo;
                    if ($vagasModalidade[$codCota]['reservas'] > 0) {
                        $convocado = false;

                        // Verifica se o candidato já foi convocado
                        foreach ($candidatosConvocados as $candidatoConvocado) {
                            if ($candidato['ds_email'] === $candidatoConvocado['ds_email']) {
                                $convocado = true;
                                break;
                            }
                        }

                        foreach ($candidatosReservas as $candidatoReserva) {
                            if ($candidato['ds_email'] === $candidatoReserva['ds_email']) {
                                $convocado = true;
                                break;
                            }
                        }

                        if ($convocado) {
                            continue;
                        } else { // Adiciona o candidato à lista de reservas
                            $candidato['cota_vaga_ocupada_id'] = Cota::firstWhere('cod_novo', $codCota)->id;
                            $candidatosReservas[] = $candidato;
                            $vagasModalidade[$codCota]['reservas']--;
                        }
                    }
                }

            /* Favor não apagar que isso ajuda a debugar se houver erro em um usuário específico
          //      if( $candidato["no_curso"] === "ADMINISTRAÇÃO" && $candidato["ds_turno"] === "Noturno")
            //        dd($candidatosDesagrupados,$nota_paulo,  $vaga_modalidade, $vaga); */
            }
        }

        // Combina todas as inscrições válidas
        $inscricoesToInsert = array_merge($candidatosConvocados, $candidatosReservas);

        // Filtra os IDs dos candidatos presentes nas inscrições
        $candidatoIds = array_column($inscricoesToInsert, 'candidato_id');

        // Filtra os candidatos e usuários que estão relacionados às inscrições
        $filteredCandidatosData = array_filter($candidatosData, function ($candidato) use ($candidatoIds) {
            return in_array($candidato['id'], $candidatoIds);
        });

        $userIds = array_column($filteredCandidatosData, 'user_id');
        $filteredUsersData = array_filter($usersData, function ($user) use ($userIds) {
            return in_array($user['id'], $userIds);
        });



        // Executa as inserções e atualizações em massa atômicamente
        DB::transaction(function () use ($filteredUsersData, $filteredCandidatosData, $inscricoesToInsert, $nextUserIdValue, $nextCandidatoIdValue) {
            User::upsert($filteredUsersData, 'id', ['name', 'updated_at']);
            Candidato::upsert($filteredCandidatosData, 'id', ['no_social', 'atualizar_dados', 'updated_at']);
           // Inscricao::insert($inscricoesToInsert);

            $batchSize = 500;
            // Divide as inscrições em lotes menores
            foreach (array_chunk($inscricoesToInsert, $batchSize) as $batch) {
                Inscricao::insert($batch);
            }

            // Atualiza o valor do próximo id da sequência
            DB::statement("SELECT setval('users_id_seq', $nextUserIdValue, false)");
            DB::statement("SELECT setval('candidatos_id_seq', $nextCandidatoIdValue, false)");
        });
    }


}
