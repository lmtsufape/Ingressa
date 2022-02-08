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
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;

class CadastroRegularCandidato implements ShouldQueue
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
        $dados = fopen(public_path('storage/'.$this->chamada->sisu->caminho_import_regular), "r");
        $primeira = true;
        while ( ($data = fgetcsv($dados,";",';') ) !== FALSE ) {
            if($primeira){
                $primeira = false;
            }else{
                $inscricao = new Inscricao([
                    'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
                    'protocolo' => Hash::make($data[8].$this->chamada->id),
                    'nu_etapa' => $data[0],
                    'no_campus' => $data[1],
                    'co_ies_curso' => $data[2],
                    'no_curso' => $data[3],
                    'ds_turno' => $data[4],
                    'ds_formacao' => $data[5],
                    'qt_vagas_concorrencia' => $data[6],
                    'co_inscricao_enem' => $data[7],
                    //'cd_efetivado' => false,
                    'tp_sexo' => $data[12],
                    'nu_rg' => $data[13],
                    'no_mae' => $data[14],
                    'ds_logradouro' => $data[15],
                    'nu_endereco' => $data[16],
                    'ds_complemento' => $data[17],
                    'sg_uf_inscrito' => $data[18],
                    'no_municipio' => $data[19],
                    'no_bairro' => $data[20],
                    'nu_cep' => $data[21],
                    'nu_fone1' => $data[22],
                    'nu_fone2' => $data[23],
                    'ds_email' => $data[24],
                    'nu_nota_l' => floatval(str_replace( ',', '.', $data[25])),
                    'nu_nota_ch' => floatval(str_replace( ',', '.', $data[26])),
                    'nu_nota_cn' => floatval(str_replace( ',', '.', $data[27])),
                    'nu_nota_m' => floatval(str_replace( ',', '.', $data[28])),
                    'nu_nota_r' => floatval(str_replace( ',', '.', $data[29])),
                    'co_curso_inscricao' => $data[30],
                    'st_opcao' => $data[31],
                    'no_modalidade_concorrencia' => $data[32],
                    'st_bonus_perc' => $data[33],
                    'qt_bonus_perc' => $data[34],
                    'no_acao_afirmativa_bonus' => $data[35],
                    'nu_nota_candidato' => floatval(str_replace( ',', '.', $data[36])),
                    'nu_notacorte_concorrida' => floatval(str_replace( ',', '.', $data[37])),
                    'nu_classificacao' => intval($data[38]),
                    'ds_matricula' => $data[39],
                    'dt_operacao' => $data[40],
                    'co_ies' => $data[41],
                    'no_ies' => $data[42],
                    'sg_ies' => $data[43],
                    'sg_uf_ies' => $data[44],
                    'st_lei_optante' => $data[45],
                    'st_lei_renda' => $data[46],
                    'st_lei_etnia_p' => $data[47],
                    'st_lei_etnia_i' => $data[48],
                ]);

                $candidatoExistente = Candidato::where('nu_cpf_inscrito', $data[10])->first();
                if($inscricao->no_modalidade_concorrencia == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
                $inscricao->no_modalidade_concorrencia == 'Ampla concorrência' || $inscricao->no_modalidade_concorrencia == 'AMPLA CONCORRÊNCIA'){
                    $cota = Cota::where('descricao',  'Ampla concorrência')->first();
                }else{
                    $cota = Cota::where('descricao', $inscricao->no_modalidade_concorrencia)->first();
                }

                if($inscricao->ds_turno == 'Matutino'){
                    $turno =  Curso::TURNO_ENUM['matutino'];
                }elseif($inscricao->ds_turno  == 'Vespertino'){
                    $turno = Curso::TURNO_ENUM['vespertino'];
                }elseif($inscricao->ds_turno == 'Noturno'){
                    $turno = Curso::TURNO_ENUM['noturno'];
                }elseif($inscricao->ds_turno == 'Integral'){
                    $turno = Curso::TURNO_ENUM['integral'];
                }

                $curs = Curso::where([['cod_curso', $inscricao->co_ies_curso], ['turno', $turno]])->first();

                $inscricao->chamada_id = $this->chamada->id;
                $inscricao->sisu_id = $this->chamada->sisu->id;
                $inscricao->cota_id = $cota->id;
                $inscricao->cota_vaga_ocupada_id = $cota->id;
                $inscricao->curso_id = $curs->id;

                if($candidatoExistente == null){
                    $user = new User([
                        'name' => $data[8],
                        'password' => Hash::make('12345678'),
                        'role' => User::ROLE_ENUM['candidato'],
                        'primeiro_acesso' => true,
                    ]);
                    if($data[9] != null){
                        $user->name = $data[9];
                    }
                    $user->save();

                    if($data[9] != null){
                        $candidato = new Candidato([
                            'no_inscrito' => $data[8],
                            'no_social' => $data[9],
                            'nu_cpf_inscrito' => $data[10],
                            'dt_nascimento' => $data[11],
                        ]);
                    }else{
                        $candidato = new Candidato([
                            'no_inscrito' => $data[8],
                            'nu_cpf_inscrito' => $data[10],
                            'dt_nascimento' => $data[11],
                        ]);
                    }
                    $candidato->user_id = $user->id;
                    $candidato->save();
                    $inscricao->candidato_id = $candidato->id;
                    $inscricao->save();
                }else{
                    $candidatoExistente->atualizar_dados = true;
                    if($data[9] != null){
                        $candidatoExistente->no_social = $data[9];
                        $candidatoExistente->user->name = $data[9];
                    }else{
                        $candidatoExistente->user->name = $data[8];
                    }
                    $candidatoExistente->update();
                    $candidatoExistente->user->update();

                    $inscricao->candidato_id = $candidatoExistente->id;
                    $inscricao->save();

                }
            }
        }
    }
}
