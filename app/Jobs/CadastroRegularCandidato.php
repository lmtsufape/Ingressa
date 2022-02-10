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
                    'protocolo' => Hash::make(strval($data[8]).$this->chamada->id),
                    'nu_etapa' => strval($data[0]),
                    'no_campus' => strval($data[1]),
                    'co_ies_curso' => strval($data[2]),
                    'no_curso' => strval($data[3]),
                    'ds_turno' => strval($data[4]),
                    'ds_formacao' => strval($data[5]),
                    'qt_vagas_concorrencia' => strval($data[6]),
                    'co_inscricao_enem' => strval($data[7]),
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
                    'dt_operacao' => DateTime::createFromFormat('d/m/Y H:i', $data[40])->format('Y/m/d'),
                    'co_ies' => strval($data[41]),
                    'no_ies' => strval($data[42]),
                    'sg_ies' => strval($data[43]),
                    'sg_uf_ies' => strval($data[44]),
                    'st_lei_optante' => strval($data[45]),
                    'st_lei_renda' => strval($data[46]),
                    'st_lei_etnia_p' => strval($data[47]),
                    'st_lei_etnia_i' => strval($data[48]),
                ]);

                $candidatoExistente = Candidato::where('nu_cpf_inscrito', strval($data[10]))->first();
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
                        'name' => strval($data[8]),
                        'password' => Hash::make('12345678'),
                        'role' => User::ROLE_ENUM['candidato'],
                        'primeiro_acesso' => true,
                    ]);
                    if($data[9] != null){
                        $user->name = strval($data[9]);
                    }
                    $user->save();

                    if($data[9] != null){
                        $candidato = new Candidato([
                            'no_inscrito' => strval($data[8]),
                            'no_social' => strval($data[9]),
                            'nu_cpf_inscrito' => strval($data[10]),
                            'dt_nascimento' => DateTime::createFromFormat('d/m/Y H:i', $data[11])->format('Y/m/d'),
                        ]);
                    }else{
                        $candidato = new Candidato([
                            'no_inscrito' => strval($data[8]),
                            'nu_cpf_inscrito' => strval($data[10]),
                            'dt_nascimento' => DateTime::createFromFormat('d/m/Y H:i', $data[11])->format('Y/m/d'),
                        ]);
                    }
                    $candidato->user_id = $user->id;
                    $candidato->save();
                    $inscricao->candidato_id = $candidato->id;
                    $inscricao->save();
                }else{
                    $candidatoExistente->atualizar_dados = true;
                    if($data[9] != null){
                        $candidatoExistente->no_social = strval($data[9]);
                        $candidatoExistente->user->name = strval($data[9]);
                    }else{
                        $candidatoExistente->user->name = strval($data[8]);
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
