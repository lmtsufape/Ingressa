<?php

namespace App\Jobs;

use App\Models\Chamada;
use App\Models\Candidato;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
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
    public $timeout = 900;
    public $tries = 1;

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
                if (!in_array($record['NU_CPF_INSCRITO'], array_column($candidatosData, 'nu_cpf_inscrito'))) {

                // Adiciona o usuário no array para inserção
                $usersData[] = [
                    'id' =>  $nextUserIdValue,
                    'name' => $record['NO_INSCRITO'],
                    'password' => '', // A senha será modificada quando o usuário acessar a conta pela primeira vez
                    'role' => User::ROLE_ENUM['candidato'],
                    'primeiro_acesso' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // Adiciona o candidato no array para inserção
                $candidatosData[] = [
                    'id' => $nextCandidatoIdValue++,
                    'no_social' => null,
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
                    'no_social' => null,
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
                    'name' => $record['NO_INSCRITO'],
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

        $keysModalidades = collect(array_keys($ordemModalidades));

        // Agrupa inscrições por curso, turno e modalidade de concorrência juntando ampla concorrência com bônus e sem bônus em uma única modalidade.
        // Após isso, ordena as modalidades de acordo com $ordemModalidades e os inscritos por nota decrescente
        $inscricoesOrdenadas = collect($inscricoesData)
            ->groupBy([
                'co_ies_curso',
                'ds_turno',
                function ($item) {
                    return Cota::getCotaModalidade($item['no_modalidade_concorrencia'])->cod_novo;
                }
            ])
            ->map(function ($cursos) use ($ordemModalidades, $keysModalidades) {
                return $cursos->map(function ($turnos) use ($ordemModalidades, $keysModalidades) {

                    $turnos = collect($turnos);

                    // cria todas as modalidades (mesmo vazias), preservando a ordem do $ordemModalidades
                    $comTodasModalidades = $keysModalidades->mapWithKeys(function ($codCota) use ($turnos) {
                        return [$codCota => collect($turnos->get($codCota, []))];
                    });

                    return $comTodasModalidades->map(function ($inscritos) {
                        return $inscritos->sortByDesc('nu_nota_candidato')->values();
                    });
                });
            });

        // Arrays para armazenar os candidatos convocados e reservas
        $candidatosConvocados = [];

        // Os candidatos estão agrupados por curso, turno e modalidade de concorrência. O primeiro foreach itera pelos curso, o segundo pelo turno e o terceiro pela modalidade e o quarto pelos candidatos.
        foreach ($inscricoesOrdenadas as $codCurso => $curso) {
            foreach ($curso as $nomeTurno => $turno) {
                $vagasModalidade = []; // Armazena a quantidade de vagas restantes para cada modalidade

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



                    // Calcula quantidade de vagas reais e reservas e armazena na chave correspondente no código da cota
                    $vagasModalidade[$codCota]['reais'] = $cotaCurso->quantidade_vagas - $cotaCurso->vagas_ocupadas;

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

                            foreach ($turno->get($remanejamento->proximaCota->cod_novo) ?? [] as $candidatos) {
                                if ($vagasModalidade[$codCota]['reais'] > 0) {
                                    $convocado = false;

                                    // Verifica se o candidato já foi convocado
                                    foreach ($candidatosConvocados as $candidatoConvocado) {
                                        if ($candidatos['ds_email'] === $candidatoConvocado['ds_email']) {
                                            $convocado = true;
                                            break;
                                        }
                                    }

                                    if ($convocado) {
                                        continue;
                                    } else { // Remaneja o candidato
                                        $candidatos['cota_vaga_ocupada_id'] = $cota->id;
                                        $candidatosConvocados[] = $candidatos;
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
            }
        }


        // Filtra os IDs dos candidatos presentes nas inscrições
        $candidatoIds = array_column($candidatosConvocados, 'candidato_id');

        // Filtra os candidatos e usuários que estão relacionados às inscrições
        $filteredCandidatosData = array_filter($candidatosData, function ($candidato) use ($candidatoIds) {
            return in_array($candidato['id'], $candidatoIds);
        });

        $userIds = array_column($filteredCandidatosData, 'user_id');
        $filteredUsersData = array_filter($usersData, function ($user) use ($userIds) {
            return in_array($user['id'], $userIds);
        });



        // Executa as inserções e atualizações em massa atômicamente
        DB::transaction(function () use ($filteredUsersData, $filteredCandidatosData, $candidatosConvocados, $nextUserIdValue, $nextCandidatoIdValue) {
            User::upsert($filteredUsersData, 'id', ['name', 'updated_at']);
            Candidato::upsert($filteredCandidatosData, 'id', ['atualizar_dados', 'updated_at']);
           // Inscricao::insert($inscricoesToInsert);

            $batchSize = 500;
            // Divide as inscrições em lotes menores
            foreach (array_chunk($candidatosConvocados, $batchSize) as $batch) {
                Inscricao::insert($batch);
            }

            // Atualiza o valor do próximo id da sequência
            DB::statement("SELECT setval('users_id_seq', $nextUserIdValue, false)");
            DB::statement("SELECT setval('candidatos_id_seq', $nextCandidatoIdValue, false)");
        });
    }


}
