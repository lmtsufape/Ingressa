<?php

namespace App\Jobs;

use App\Models\Chamada;
use App\Models\Candidato;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CadastroRegularCandidato implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $chamada;
    protected $cotas;
    protected $cursos;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Chamada $chamada)
    {
        $this->chamada = $chamada->load('sisu');
        $this->cotas = Cota::all();
        $this->cursos = Curso::all();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Caminho do arquivo CSV
        $csvPath = storage_path("app/{$this->chamada->sisu->caminho_import_regular}");

        // Lendo o arquivo CSV
        $csv = Reader::from($csvPath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);
        $records = iterator_to_array($csv->getRecords(), true);


        // Arrays para armazenar os dados dos usuários, candidatos e inscrições
        $usersData = [];
        $candidatosData = [];
        $inscricoesData = [];

        // Otimização para pegar apenas os candidatos que já estão cadastrados e usar indexação para tornar a busca mais rápida
        $cpfInscritos = array_column($records, 'NU_CPF_INSCRITO');
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
                    'id' => $nextCandidatoIdValue,
                    'no_social' => null,
                    'no_inscrito' => $record['NO_INSCRITO'],
                    'nu_cpf_inscrito' => $record['NU_CPF_INSCRITO'],
                    'dt_nascimento' => Carbon::createFromFormat('Y-m-d H:i:s', $record['DT_NASCIMENTO'])->format('Y-m-d H:i:s'),
                    'etnia_e_cor' => Candidato::ETNIA_E_COR[$record['COR_RACA']],
                    'user_id' => $nextUserIdValue++,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'atualizar_dados' => true,
                ];

            // Atualiza dados do candidato caso ele exista
            } else {
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

            // Adicionando inscrição
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
                'nu_nota_l' => isset($record['NU_NOTA_L']) ? floatval(str_replace(',', '.', $record['NU_NOTA_L'])) : null,
                'nu_nota_ch' => isset($record['NU_NOTA_CH']) ? floatval(str_replace(',', '.', $record['NU_NOTA_CH'])) : null,
                'nu_nota_cn' => isset($record['NU_NOTA_CN']) ? floatval(str_replace(',', '.', $record['NU_NOTA_CN'])) : null,
                'nu_nota_m' => isset($record['NU_NOTA_M']) ? floatval(str_replace(',', '.', $record['NU_NOTA_M'])) : null,
                'nu_nota_r' => isset($record['NU_NOTA_R']) ? floatval(str_replace(',', '.', $record['NU_NOTA_R'])) : null,
                'co_curso_inscricao' => $record['CO_CURSO_INSCRICAO'],
                'st_opcao' => $record['ST_OPCAO'],
                'no_modalidade_concorrencia' => $record['NO_MODALIDADE_CONCORRENCIA'],
                'st_bonus_perc' => $record['ST_BONUS_PERC'],
                'qt_bonus_perc' => $record['QT_BONUS_PERCENTUAL'],
                'no_acao_afirmativa_bonus' => $record['NO_ACAO_AFIRMATIVA_BONUS'],
                'nu_nota_candidato' => isset($record['NU_NOTA_CANDIDATO']) ? floatval(str_replace(',', '.', $record['NU_NOTA_CANDIDATO'])) : null,
                'nu_notacorte_concorrida' => isset($record['NU_NOTACORTE_CONCORRIDA']) ? floatval(str_replace(',', '.', $record['NU_NOTACORTE_CONCORRIDA'])) : null,
                'nu_classificacao' => isset($record['NU_CLASSIFICACAO']) ? intval($record['NU_CLASSIFICACAO']) : null,
                'ds_matricula' => $record['DS_MATRICULA'],
                'dt_operacao' => !empty($record['DT_OPERACAO']) ? Carbon::createFromFormat('Y-m-d H:i:s', $record['DT_OPERACAO'])->format('Y-m-d H:i:s') : null,
                'co_ies' => $record['CO_IES'],
                'no_ies' => $record['NO_IES'],
                'sg_ies' => $record['SG_IES'],
                'sg_uf_ies' => $record['SG_UF_IES'],
                'ensino_medio' => $record['ENSINO_MEDIO'],
                'quilombola' => $record['QUILOMBOLA'],
                'deficiente' => $record['PcD'],
                'st_rank_ensino_medio' => $record['ST_RANK_ENSINO_MEDIO'],
                'st_rank_raca' => $record['ST_RANK_RACA'],
                'st_rank_quilombola' => $record['ST_RANK_QUILOMBOLA'],
                'st_rank_pcd' => $record['ST_RANK_PcD'],
                'st_confirma_lgpd' => $record['ST_CONFIRMA_LGPD'],
                // 'total_membros_familiar' => intval($record['TOTAL_MEMBROS_FAMILIAR']),
                // 'renda_familiar_bruta' => floatval(str_replace(',', '.', $record['RENDA_FAMILIAR_BRUTA'])),
                'salario_minimo' => floatval(str_replace(',', '.', $record['SALARIO_MINIMO'])),
                'perfil_economico_lei_cotas' => $record['PERFIL_ECONOMICO_LEI_COTAS'],
                'dt_curso_inscricao' => !empty($record['DT_CURSO_INSCRICAO']) ? Carbon::createFromFormat('Y-m-d H:i:s', $record['DT_CURSO_INSCRICAO'])->format('Y-m-d') : null,
                'hr_curso_inscricao' => $record['HR_CURSO_INSCRICAO'],
                'dt_mes_dia_inscricao' => !empty($record['DT_MES_DIA_INSCRICAO']) ? Carbon::createFromFormat('m/d', $record['DT_MES_DIA_INSCRICAO'])->format('Y-m-d') : null,
                'nu_nota_curso_l' => floatval(str_replace(',', '.', $record['NU_NOTA_CURSO_L'])),
                'nu_nota_curso_ch' => floatval(str_replace(',', '.', $record['NU_NOTA_CURSO_CH'])),
                'nu_nota_curso_cn' => floatval(str_replace(',', '.', $record['NU_NOTA_CURSO_CN'])),
                'nu_nota_curso_m' => floatval(str_replace(',', '.', $record['NU_NOTA_CURSO_M'])),
                'nu_nota_curso_r' => floatval(str_replace(',', '.', $record['NU_NOTA_CURSO_R'])),
                'st_adesao_acao_afirmativa_curs' => $record['ST_ADESAO_ACAO_AFIRMATIVA_CURS'],
                'media_simples_pdm_licenca' => floatval(str_replace(',', '.', $record['MEDIA_SIMPLES_PDM_LICENC'])),
                'st_baixa_renda' => $record['ST_BAIXA_RENDA'],
                'st_rank_baixa_renda' => $record['ST_RANK_BAIXA_RENDA'],
                'st_adesao_acao_afirmativa_curso' => $record['ST_ADESAO_ACAO_AFIRMATIVA_CURS'],
                'st_aprovado' => $record['ST_APROVADO'],
                'dt_mes_dia_matricula' => empty(!$record['DT_MES_DIA_MATRICULA']) ? Carbon::createFromFormat('Y-m-d H:i:s', $record['DT_MES_DIA_MATRICULA'])->format('Y-m-d H:i:s') : null,
                'st_matricula_cancelada' => $record['ST_MATRICULA_CANCELADA'],
                'dt_matricula_cancelada' => empty(!$record['DT_MATRICULA_CANCELADA']) ? Carbon::createFromFormat('d/m/Y', $record['DT_MATRICULA_CANCELADA'])->format('Y-m-d') : null,
                'vaga_remanejada' => $record['VAGA_REMANEJADA'],
                'no_acao_afirmativa_propria_ies' => $record['NO_ACAO_AFIRMATIVA_PROPRIA_IES'],
                'tipo_concorrencia' => $record['TIPO_CONCORRENCIA'],
                'chamada_id' => $this->chamada->id,
                'sisu_id' => $this->chamada->sisu->id,
                'cota_id' => Cota::getCotaModalidade($record['NO_MODALIDADE_CONCORRENCIA'])->id,
                'cota_vaga_ocupada_id' => !empty($record['VAGA_REMANEJADA']) ? Cota::getCotaModalidade($record['VAGA_REMANEJADA'])->id : Cota::getCotaModalidade($record['NO_MODALIDADE_CONCORRENCIA'])->id,
                'candidato_id' => $candidato ? $candidato->id : $nextCandidatoIdValue++,
                'curso_id' => $this->cursos
                    ->where('cod_curso', $record['CO_IES_CURSO'])
                    ->where('turno', Curso::TURNO_ENUM[$record['DS_TURNO']])
                    ->first()
                    ->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Executa as inserções e atualizações em massa atômicamente
        DB::transaction(function () use ($usersData, $candidatosData, $inscricoesData, $nextUserIdValue, $nextCandidatoIdValue) {
            User::upsert($usersData, 'id', ['name', 'updated_at']);
            Candidato::upsert($candidatosData, 'id', ['atualizar_dados', 'updated_at']);
            Inscricao::insert($inscricoesData);

            // Atualiza o valor do próximo id da sequência
            DB::statement("SELECT setval('users_id_seq', $nextUserIdValue, false)");
            DB::statement("SELECT setval('candidatos_id_seq', $nextCandidatoIdValue, false)");
        });
    }
}
