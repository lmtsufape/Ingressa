<?php

namespace App\Jobs;

use App\Models\Chamada;
use App\Models\Candidato;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $this->chamada = $chamada;
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
        $csvPath = storage_path('app' . DIRECTORY_SEPARATOR . $this->chamada->sisu->caminho_import_regular);

        // Lendo o arquivo CSV
        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // Define a primeira linha como cabeçalho
        $records = $csv->getRecords(); // Iterador com os registros

        $usersData = []; // Array para armazenar os dados dos usuários
        $candidatosData = []; // Array para armazenar os dados dos candidatos
        $inscricoesData = []; // Array para armazenar os dados das inscrições
        $candidatosUpdate = []; // Array para armazenar os candidatos que precisam ser atualizados
        $cpfInscritos = array_column(iterator_to_array($records), 'nu_cpf_inscrito');
        $candidatos = Candidato::whereIn('nu_cpf_inscrito', $cpfInscritos)->get()->keyBy('nu_cpf_inscrito'); // Otimização para pegar apenas os candidatos que já estão cadastrados e tornar a busca mais rápida

        foreach ($records as $record) {
            $candidato = $candidatos->get($record['NU_CPF_INSCRITO']);

            $userId = null;
            $candidatoId = null;

            if (!$candidato) { // Cria um novo candidato e usuário caso ele não exista
                // Adicionando usuário
                $userId = Str::uuid();
                $usersData[] = [
                    'id' =>  $userId,
                    'name' => $record['NO_SOCIAL'] ?? $record['NO_INSCRITO'],
                    'password' => Hash::make('12345678'),
                    'role' => User::ROLE_ENUM['candidato'],
                    'primeiro_acesso' => true,
                ];

                // Adicionando candidato
                $candidatoId = Str::uuid();
                $candidatosData[] = [
                    'id' => $candidatoId,
                    'no_social' => $record['NO_SOCIAL'],
                    'no_inscrito' => $record['NO_INSCRITO'],
                    'nu_cpf_inscrito' => $record['NU_CPF_INSCRITO'],
                    'dt_nascimento' => (new DateTime($record['DT_NASCIMENTO']))->format('Y-m-d'),
                    'etnia_e_cor' => Candidato::ETNIA_E_COR[$record['ETNIA_E_COR']],
                    'user_id' => $userId,
                ];
            } else { // Atualiza dados do candidato caso ele exista
                $candidato->atualizar_dados = true;
                $candidato->no_social = $record['NO_SOCIAL'];
                $candidato->user->name = $record['NO_SOCIAL'] ?? $record['NO_INSCRITO'];
                $candidatoId = $candidato->id;
                $candidatosUpdate[] = $candidato;
            }

            $inscricoesData[] = [
                'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
                'protocolo' => Hash::make(strval($record['CO_INSCRICAO_ENEM']) . $this->chamada->id),
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
                'qt_bonus_perc' => $record['QT_BONUS_PERC'],
                'no_acao_afirmativa_bonus' => $record['NO_ACAO_AFIRMATIVA_BONUS'],
                'nu_nota_candidato' => isset($record['NU_NOTA_CANDIDATO']) ? floatval(str_replace(',', '.', $record['NU_NOTA_CANDIDATO'])) : null,
                'nu_notacorte_concorrida' => isset($record['NU_NOTACORTE_CONCORRIDA']) ? floatval(str_replace(',', '.', $record['NU_NOTACORTE_CONCORRIDA'])) : null,
                'nu_classificacao' => isset($record['NU_CLASSIFICACAO']) ? intval($record['NU_CLASSIFICACAO']) : null,
                'ds_matricula' => $record['DS_MATRICULA'],
                'dt_operacao' => isset($record['DT_OPERACAO']) ? DateTime::createFromFormat('Y-m-d H:i:s', $record['DT_OPERACAO'])->format('Y/m/d') : null,
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
                'quilombola' => $record['QUILOMBOLA'],
                'deficiente' => $record['DEFICIENTE'],
                'modalidade_escolhida' => $record['MODALIDADE_ESCOLHIDA'],
                'tipo_concorrencia' => $record['TIPO_CONCORRENCIA'],
                'chamada_id' => $this->chamada->id,
                'sisu_id' => $this->chamada->sisu->id,
                'cota_id' => $this->getCotaModalidade($record['NO_MODALIDADE_CONCORRENCIA']),
                'cota_vaga_ocupada_id' => $this->getCotaModalidade($record['NO_MODALIDADE_CONCORRENCIA']),
                'candidato_id' => $candidatoId,
                'curso_id' => $this->cursos->where('cod_curso', $record['CO_IES_CURSO'])
                                     ->where('turno', Curso::TURNO_ENUM[$record['DS_TURNO']])
                                     ->first()
                                     ->id,
            ];
        }

        // Inserção em massa
        DB::transaction(function () use ($usersData, $candidatosData, $inscricoesData, $candidatosUpdate) {
            User::insert($usersData);
            Candidato::insert($candidatosData);
            Inscricao::insert($inscricoesData);

            // Atualização dos candidatos existentes
            foreach ($candidatosUpdate as $candidato) {
                $candidato->save();
                $candidato->user->save();
            }
        });
    }

    private function getCotaModalidade($modalidade)
    {
        if (
            $modalidade == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.'
            || $modalidade == 'AMPLA CONCORRÊNCIA'
            || $modalidade == 'Ampla concorrência'
        ) {
            return $this->cotas->where('cod_cota', 'A0')->first()->id;
        }

        return $this->cotas->where('nome', $modalidade)->first()->id;
    }
}
