<?php

namespace App\Console\Commands;

use App\Models\Avaliacao;
use App\Models\Chamada;
use App\Models\Inscricao;
use Illuminate\Console\Command;

class CorrigirStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'corrigir:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corrige o status dos candidatos da ultima chamada criada';

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
        $chamada = Chamada::orderBy('created_at', 'DESC')->get()->first();
        //$chamada = Chamada::find(1);

        //Itera os candidatos da ultima chamda 
        foreach($chamada->inscricoes as $inscricao){
            $documentosAceitos = true;
            $necessitaAvaliar = false;

            //Entao para cada arquivo que o candidato enviou
            foreach($inscricao->arquivos as $arqui){
                //Verifica se o arquivo foi avaliado
                if(!is_null($arqui->avaliacao)){
                    //Caso tenha sido avaliado e recusado, entao e um sinal que os docs nao foram aceitos
                    if($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['recusado']){
                        $documentosAceitos = false;
                    //Caso algum arquivo tenha sido reenviado entao e necessario avaliar os documentos e eles nao foram aceitos
                    }elseif($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['reenviado']){
                        $documentosAceitos = false;
                        $necessitaAvaliar = true;
                        break;
                    }
                //caso o arquivo ainda nao possua avaliacao, significa que deve-se avaliar ainda e os documentos nao foram todos aceitos
                }else{
                    $documentosAceitos = false;
                    $necessitaAvaliar = true;
                    break;
                }
            }

            //Vamos deixar guardado o status do candidato para uma correcao
            $statusInicial = $inscricao->status;
            
            //Apos essa verificacao dos status dos documentos

            //caso todos os documentos iterados tenham sido aceitos
            if($documentosAceitos){
                //veremos se existe algum doc que nao foi enviado
                $diferenca = array_diff($this->todosDocsRequisitados($inscricao->id)->toArray(), $inscricao->arquivos->pluck('nome')->toArray());
                if(count($diferenca) == 0){
                    //caso todos tenham sido enviados e todos foram aceitos passando na variavel de $documentosAceitos, entao setamos esse status
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
                }else{
                    //Caso o candidato nao tenha nenhum arquivo enviado, ele vai passar com a variavel de $documentosAceitos
                    //porem o status de documentos e pendente e deve permancer nele
                    if($inscricao->arquivos->first() == null){
                        $inscricao->status = Inscricao::STATUS_ENUM['documentos_pendentes'];
                    }else{
                        //caso todos os docs foram aprovados mas ele nao enviou todos, entao o status e este
                        $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'];
                    }
                }
            //Caso os docs nao tenham sido aceitos
            }else{
                //verificamos se ainda precisa avaliar
                if($necessitaAvaliar == true){
                    //caso precise entao setamos o status neutro de documentos enviados, pois ainda nao foi concluida a avaliacao do analista
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
                }else{
                    //caso contrario os documentos foram invalidos, ja que nao foram aceitos e nao precisa avaliar mais nada ainda
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_invalidados'];
                }
            }

            /*Caso o candidato tenha tido problemas no envio dos documentos e tenha so anexado, ele nao saiu do estado de pendentes
            e pode ainda possuir documentos obrigatorios nao enviados. Sendo assim, deve permanecer nesse estado ate que faca o envio dos
            documentos submetendo o formulario*/

            //Então, verificamos qual era o status inicial do candidato para que se caso for pendentes, que ele permaneça nesse estado
            if($statusInicial == Inscricao::STATUS_ENUM['documentos_pendentes']){
                $inscricao->status = Inscricao::STATUS_ENUM['documentos_pendentes'];
            }

            $inscricao->update();
        }
    }

    public function todosDocsRequisitados($id)
    {
        $inscricao = Inscricao::find($id);
        $documentos = collect();

        $documentos->push('declaracao_veracidade');
        $documentos->push('certificado_conclusao');
        $documentos->push('historico');
        $documentos->push('nascimento_ou_casamento');
        $documentos->push('cpf');
        $documentos->push('rg');
        $documentos->push('quitacao_eleitoral');
        if($inscricao->tp_sexo == 'M'){
            $documentos->push('quitacao_militar');
        }
        $documentos->push('foto');
        if($inscricao->st_lei_etnia_i == 'S' && $inscricao->candidato->cor_raca == 5){
            $documentos->push('rani');
            $documentos->push('declaracao_cotista');
        }
        if($inscricao->st_lei_etnia_p == 'S' && in_array($inscricao->candidato->cor_raca, [2, 3])){
            $documentos->push('heteroidentificacao');
            $documentos->push('fotografia');
            if(!$documentos->contains('declaracao_cotista')){
                $documentos->push('declaracao_cotista');
            }
        }
        if($inscricao->st_lei_renda == 'S'){
            $documentos->push('comprovante_renda');
            if(!$documentos->contains('declaracao_cotista')){
                $documentos->push('declaracao_cotista');
            }
        }
        if(str_contains($inscricao->no_modalidade_concorrencia, 'deficiência')){
            $documentos->push('laudo_medico');
            if(!$documentos->contains('declaracao_cotista')){
                $documentos->push('declaracao_cotista');
            }
        }
        if($inscricao->cota->cod_cota == 'L5' || $inscricao->cota->cod_cota == 'L6'){
            if(!$documentos->contains('declaracao_cotista')){
                $documentos->push('declaracao_cotista');
            }
        }

        return $documentos;
    }
}
