<?php

namespace Database\Seeders;

use App\Models\TipoAnalista;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'role'           => User::ROLE_ENUM['admin'],
                'primeiro_acesso' => false,
            ],
            [
                'name'           => 'Analista',
                'email'          => 'analista@analista.com',
                'password'       => bcrypt('password'),
                'role'           => User::ROLE_ENUM['analista'],
                'primeiro_acesso' => false,
            ],
            [
                'name'           => 'Candidato',
                'email'          => 'candidato@candidato.com',
                'password'       => bcrypt('password'),
                'role'           => User::ROLE_ENUM['candidato'],
                'primeiro_acesso' => true,
            ],
        ];

        User::insert($users);
        $user = User::find(3);
        $user->candidato()->create([
            'no_inscrito'            => fake()->name(),
            'no_social'              => fake()->optional()->name(),
            'nu_cpf_inscrito'        => fake()->unique()->numerify('###########'),
            'dt_nascimento'          => fake()->date(),

            'orgao_expedidor'        => fake()->randomElement(['SSP', 'DETRAN', 'IFP']),
            'uf_rg'                  => fake()->stateAbbr(),
            'data_expedicao'         => fake()->date(),

            'titulo'                 => fake()->numerify('############'),
            'zona_eleitoral'         => (string) fake()->numberBetween(1, 999),
            'secao_eleitoral'        => (string) fake()->numberBetween(1, 9999),

            'cidade_natal'           => fake()->city(),
            'reside'                 => fake()->city(),
            'uf_natural'             => fake()->stateAbbr(),
            'pais_natural'           => 'Brasil',

            'estado_civil'           => fake()->randomElement(['Solteiro(a)', 'Casado(a)', 'Divorciado(a)', 'Viúvo(a)']),
            'pai'                    => fake()->optional()->name('male'),
            'localidade'             => fake()->streetName(),

            'escola_ens_med'         => fake()->company(),
            'uf_escola'              => fake()->stateAbbr(),
            'ano_conclusao'          => fake()->numberBetween(2000, (int) date('Y')),

            'modalidade'             => fake()->randomElement(['Regular', 'EJA', 'Técnico', 'Outro']),
            'concluiu_publica'       => fake()->boolean(),
            'concluiu_comunitaria'   => fake()->boolean(),

            'necessidades'           => fake()->optional()->randomElement(['Nenhuma', 'Auditiva', 'Visual', 'Motora', 'Intelectual']),
            'etnia_e_cor'            => fake()->randomElement(['Branca', 'Preta', 'Parda', 'Amarela', 'Indígena']),
            'trabalha'               => fake()->boolean(),

            'grupo_familiar'         => fake()->numberBetween(1, 8),
            'valor_renda'            => fake()->randomFloat(2, 0, 10000),

            'atualizar_dados'        => true,
            'quilombola'             => fake()->boolean(),
            'indigena'               => fake()->boolean(),
        ]);

        $user->candidato->inscricoes()->create([
            'chamada_id' => 1,
            'cota_id' => 1,
            'curso_id' => 1,
            'sisu_id' => 1,
            'ds_matricula' => 'sdfsdf',
            'protocolo' => fake()->bothify('PROTO-########'),
            'protocolo_envio' => fake()->optional()->bothify('ENV-########'),
            'status' => fake()->randomElement(['PENDENTE', 'ENVIADO', 'DEFERIDO', 'INDEFERIDO']),
            'cd_efetivado' => fake()->boolean(),
            'retificacao' => fake()->boolean(),
            'justificativa' => fake()->optional()->sentence(),
            'nu_etapa' => fake()->numberBetween(1, 5),

            'no_campus' => fake()->city(),
            'co_ies_curso' => (string) fake()->numberBetween(100000, 999999),
            'no_curso' => fake()->randomElement(['Sistemas de Informação', 'Administração', 'Direito', 'Enfermagem']),
            'ds_turno' => fake()->randomElement(['Matutino', 'Vespertino', 'Noturno', 'Integral']),
            'ds_formacao' => fake()->randomElement(['Bacharelado', 'Licenciatura', 'Tecnólogo']),
            'qt_vagas_concorrencia' => fake()->numberBetween(1, 120),

            'co_inscricao_enem' => (string) fake()->numberBetween(1000000000, 9999999999),

            'tp_sexo' => fake()->randomElement(['M', 'F']),
            'nu_rg' => fake()->numerify('#########'),
            'no_mae' => fake()->name('female'),

            'ds_logradouro' => fake()->streetName(),
            'nu_endereco' => fake()->buildingNumber(),
            'ds_complemento' => fake()->secondaryAddress(),
            'sg_uf_inscrito' => fake()->stateAbbr(),
            'no_municipio' => fake()->city(),
            'no_bairro' => fake()->word(),
            'nu_cep' => fake()->numerify('########'),

            'nu_fone1' => fake()->numerify('###########'),
            'nu_fone2' => fake()->optional()->numerify('###########'),
            'nu_fone_emergencia' => fake()->optional()->numerify('###########'),
            'ds_email' => fake()->unique()->safeEmail(),

            'nu_nota_l' => fake()->randomFloat(2, 0, 1000),
            'nu_nota_ch' => fake()->randomFloat(2, 0, 1000),
            'nu_nota_cn' => fake()->randomFloat(2, 0, 1000),
            'nu_nota_m' => fake()->randomFloat(2, 0, 1000),
            'nu_nota_r' => fake()->randomFloat(2, 0, 1000),

            'co_curso_inscricao' => '13',
            'st_opcao' => fake()->randomElement([1, 2]),
            'no_modalidade_concorrencia' => fake()->randomElement(['Ampla Concorrência', 'Cotas', 'Ações Afirmativas']),

            'st_bonus_perc' => fake()->boolean(),
            'qt_bonus_perc' => fake()->randomFloat(2, 0, 20),
            'no_acao_afirmativa_bonus' => fake()->optional()->randomElement(['PPI', 'Renda', 'Escola pública']),

            'nu_nota_candidato' => fake()->randomFloat(2, 0, 1000),
            'nu_notacorte_concorrida' => fake()->randomFloat(2, 0, 1000),
            'nu_classificacao' => fake()->numberBetween(1, 10000),

            'dt_operacao' => fake()->dateTimeBetween('-2 years', 'now'),

            'co_ies' => (string) fake()->numberBetween(1000, 999999),
            'no_ies' => fake()->randomElement(['UFAPE', 'UFPE', 'UPE', 'IFPE']),
            'sg_ies' => fake()->randomElement(['UFAPE', 'UFPE', 'UPE', 'IFPE']),
            'sg_uf_ies' => fake()->stateAbbr(),

            'st_lei_optante' => fake()->boolean(),
            'st_lei_renda' => fake()->boolean(),
            'st_lei_etnia_p' => fake()->boolean(),
            'st_lei_etnia_i' => fake()->boolean(),
            'de_acordo_lei_cota' => fake()->boolean(),

            'quilombola' => fake()->boolean(),
            'deficiente' => fake()->boolean(),

            'modalidade_escolhida' => fake()->randomElement(['Ampla', 'Cotas', 'PPI', 'Renda']),
            'tipo_concorrencia' => fake()->randomElement(['AC', 'COTAS', 'PPI', 'Renda']),
        ]);


        DB::table('tipo_analista_user')->insert([
            'user_id' => 2,
            'tipo_analista_id' => 1,
        ]);
    }
}
