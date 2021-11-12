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
        $dados = fopen(public_path('storage/'.$this->chamada->caminho_import_sisu_gestao), "r");
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
                    'status' => Inscricao::STATUS_ENUM['documentos_requeridos'],
                    'protocolo' => Hash::make($data[8].$this->chamada->id),
                    'nu_etapa' => $data[0],
                    'no_campus' => $data[1],
                    'co_ies_curso' => $data[2],
                    'no_curso' => $data[3],
                    'ds_turno' => $data[4],
                    'ds_formacao' => $data[5],
                    'qt_vagas_concorrencia' => $data[6],
                    'co_inscricao_enem' => $data[7],

                    'no_inscrito' => $data[8],
                    'no_social' => $data[9],
                    'nu_cpf_inscrito' => $data[10],
                    'dt_nascimento' => $data[11],

                    'cd_efetivado' => false,
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
                    'nu_nota_l' => floatval($data[25]),
                    'nu_nota_ch' => floatval($data[26]),
                    'nu_nota_cn' => floatval($data[27]),
                    'nu_nota_m' => floatval($data[28]),
                    'nu_nota_r' => floatval($data[29]),
                    'co_curso_inscricao' => $data[30],
                    'st_opcao' => $data[31],
                    'no_modalidade_concorrencia' => $data[32],
                    'st_bonus_perc' => $data[33],
                    'qt_bonus_perc' => $data[34],
                    'no_acao_afirmativa_bonus' => $data[35],
                    'nu_nota_candidato' => floatval($data[36]),
                    'nu_notacorte_concorrida' => floatval($data[37]),
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

        //Feito isto, é necessário juntar todos os inscritos da ampla concorrencia independete da cota de 10%
        foreach($porCurso as $curso){
            $modalidade = collect();
            $ampla = collect();
            $modalidades = collect();
            foreach($curso as $porModalidade){
                //os de ampla são colocados em um único collection aqui
                if($porModalidade[0]['no_modalidade_concorrencia'] == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
                    $porModalidade[0]['no_modalidade_concorrencia'] == 'AMPLA CONCORRÊNCIA' || $porModalidade[0]['no_modalidade_concorrencia'] == 'Ampla concorrência'){
                    $ampla = $ampla->concat($porModalidade);
                }else{
                    $modalidade = $porModalidade;
                    $modalidade = $modalidade->sortBy(function($candidato){
                        return $candidato['nu_nota_candidato'];
                    });
                    $modalidades->push($modalidade);
                }
            }
            //ordenamos os inscritos da modalidade daquele curso pela nota
            $ampla = $ampla->sortByDesc(function($candidato){
                return $candidato['nu_nota_candidato'];
            });
            $modalidades->push($ampla);
            $cursos->push($modalidades);
        }

        //Preparados os dados dos inscritos, agora criaremos as instancias para salvar no banco
        foreach($cursos as $curso){
            foreach($curso as $porModalidade){
                $candidato = $porModalidade[0];
                //Recuperamos a cota que aquele inscrito está relacionado
                if($candidato['no_modalidade_concorrencia'] == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
                $candidato['no_modalidade_concorrencia'] == 'Ampla concorrência' || $candidato['no_modalidade_concorrencia'] == 'AMPLA CONCORRÊNCIA'){
                    $cota = Cota::where('descricao',  'Ampla concorrência')->first();
                }else{
                    $cota = Cota::where('descricao', $candidato['no_modalidade_concorrencia'])->first();
                }
                //E pegamos a informação de quantas vagas temos tem restante baseado em quantos candidatos foram efetivados e quantas vagas são
                //ofertadas para aquela cota

                if($candidato['ds_turno'] == 'Matutino'){
                    $turno =  Curso::TURNO_ENUM['matutino'];
                }elseif($candidato['ds_turno'] == 'Vespertino'){
                    $turno = Curso::TURNO_ENUM['vespertino'];
                }elseif($candidato['ds_turno'] == 'Noturno'){
                    $turno = Curso::TURNO_ENUM['noturno'];
                }elseif($candidato['ds_turno'] == 'Integral'){
                    $turno = Curso::TURNO_ENUM['integral'];
                }

                $curs = Curso::where([['cod_curso', $candidato['co_ies_curso']], ['turno', $turno]])->first();
                $cota_curso = $curs->cotas()->where('cota_id', $cota->id)->first()->pivot;

                $vagasCota = $cota_curso->quantidade_vagas - $cota_curso->vagas_ocupadas;
                //chamamos o número de vagas disponíveis vezes um valor k
                $vagasCota *= 3;
                //enquanto houver vagas e inscritos daquela modalidade, o laço irá continuar
                foreach($porModalidade as $inscrito){
                    if($vagasCota > 0){

                        //agora podemos preparar o objeto de inscricao para o candidato
                        $inscricao = new Inscricao([
                            'status' => Inscricao::STATUS_ENUM['documentos_requeridos'],
                            'protocolo' => Hash::make($inscrito['protocolo'].$this->chamada->id),
                            'nu_etapa' => $inscrito['nu_etapa'],
                            'no_campus' => $inscrito['no_campus'],
                            'co_ies_curso' => $inscrito['co_ies_curso'],
                            'no_curso' => $inscrito['no_curso'],
                            'ds_turno' => $inscrito['ds_turno'],
                            'ds_formacao' => $inscrito['ds_formacao'],
                            'qt_vagas_concorrencia' => $inscrito['qt_vagas_concorrencia'],
                            'co_inscricao_enem' => $inscrito['co_inscricao_enem'],
                            'cd_efetivado' => false,
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

                        //recuperamos se o inscrio possui um usuário no sistema
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
                            $inscricao->candidato_id = $candidato->id;
                            $inscricao->cota_id = $cota->id;
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
                                if($inscrito['no_social'] != null){
                                    $candidatoExistente->no_social = $inscrito['no_social'];
                                    $candidatoExistente->update();
                                    $candidatoExistente->user->name = $inscrito['no_social'];
                                }else{
                                    $candidatoExistente->user->name = $inscrito['no_inscrito'];
                                }
                                $candidatoExistente->user->update();

                                $inscricao->chamada_id = $this->chamada->id;
                                $inscricao->candidato_id = $candidatoExistente->id;
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
            }
        }
    }
}
