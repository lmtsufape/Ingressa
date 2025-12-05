<?php

namespace App\Console\Commands;

use App\Exports\InscritosExport;
use App\Models\Candidato;
use App\Models\Inscricao;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportarDadosInscricoes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exportar:inscritos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Importação iniciada!');

        $etnia_cor = json_encode(array_flip(Candidato::ETNIA_E_COR));
        $necessidades = json_encode(Candidato::NECESSIDADES);

        $inscritos = Inscricao::query()->from('inscricaos')
            ->leftJoin('candidatos as candidato', 'candidato.id', '=', 'inscricaos.candidato_id')
            ->leftJoin('sisus as sisu', 'sisu.id', '=', 'sisu_id')
            ->leftJoin('chamadas as chamada', 'chamada.id', '=', 'chamada_id')
            ->leftJoin('cotas as cota_ocupada', 'cota_ocupada.id', '=', 'cota_vaga_ocupada_id')
            ->whereIn('edicao', ['2024', '2025'])
            ->where(function($query){
                $query->where('cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'])
                    ->Where('desistente', false)
                    ->whereNotNull('semestre_entrada');
            })
            ->select([
                'no_inscrito',
                'no_social',
                DB::raw("to_char(dt_nascimento, 'DD/MM/YYYY') as dt_nascimento"),
                'tp_sexo',
                DB::raw("CASE estado_civil::int
                            WHEN 1 THEN 'Solteiro(a)'
                            WHEN 2 THEN 'Casado(a)'
                            WHEN 3 THEN 'Separado(a) judicialmente'
                            WHEN 4 THEN 'Divorciado(a)'
                            WHEN 5 THEN 'Viuvo(a)'
                            ELSE '—'
                        END as estado_civil"),
                'nu_cpf_inscrito',
                'nu_rg',
                'orgao_expedidor',
                'uf_rg',
                DB::raw("to_char(data_expedicao, 'DD/MM/YYYY') as data_expedicao"),
                'titulo',
                'zona_eleitoral',
                'secao_eleitoral',
                'cidade_natal',
                'uf_natural',
                'pais_natural',
                'no_mae',
                'pai',
                'no_campus',//unidade
                'ds_formacao',//bacharelado ou licenciatura
                'ds_turno',
                DB::raw("'SiSU' as forma_ingress"),
                'edicao',//seria o ano de ingresso??
                'semestre_entrada',
                DB::raw("CASE WHEN regular IS TRUE THEN 'Regular' ELSE 'Lista de espera' END AS tipo_chamada"),
                'nu_nota_l',
                'nu_nota_ch',
                'nu_nota_cn',
                'nu_nota_m',
                'nu_nota_r',
                'nu_nota_candidato',
                'no_curso',
                'modalidade_escolhida',
                'cota_ocupada.nome',//modalidade ocupada
                'ds_logradouro',
                'nu_endereco',
                'nu_cep',
                'ds_complemento',
                'no_municipio',
                'no_bairro',
                'sg_uf_inscrito',
                'nu_fone1',
                'nu_fone2',
                'nu_fone_emergencia',
                'ds_email',
                'escola_ens_med',//nome da escola
                'uf_escola',
                'ano_conclusao',
                'modalidade',
                DB::raw("CASE WHEN concluiu_publica IS TRUE THEN 'SIM' ELSE 'NÃO' END AS concluiu_publica"),
                DB::raw("CASE WHEN concluiu_comunitaria IS TRUE THEN 'SIM' ELSE 'NÃO' END AS concluiu_comunitaria"),
                DB::raw("COALESCE((CAST(? AS jsonb) ->> (candidato.necessidades)::text), 'Nenhuma') AS necessidades_label"),
                DB::raw("COALESCE((CAST(? AS jsonb) ->> (candidato.etnia_e_cor)::text), 'Não informada') AS etnia_cor_label"),

                DB::raw("CASE WHEN inscricaos.quilombola = 'S' THEN 'SIM' ELSE 'NÃO' END AS quilombola"),
                DB::raw("CASE WHEN indigena IS TRUE THEN 'SIM' ELSE 'NÃO' END AS indigena"),
                'reside',//moradia atual
                'localidade',
                DB::raw("CASE WHEN trabalha IS TRUE THEN 'SIM' ELSE 'NÃO' END AS trabalha"),//se trabalha
                'grupo_familiar',// quantidade de pessoas da familia
                'valor_renda']);
                $inscritos->addBinding($necessidades, 'select');
                $inscritos->addBinding($etnia_cor, 'select');

            $path = 'exports/sisu_gestao.csv';

            Excel::store(
                new InscritosExport($inscritos),
                $path,
                'local',
                \Maatwebsite\Excel\Excel::CSV
            );
        $this->info('Importação concluída com sucesso!');
    }
}
