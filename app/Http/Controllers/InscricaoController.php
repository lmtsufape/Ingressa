<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Avaliacao;
use App\Models\Candidato;
use App\Models\Chamada;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Throwable;
use ZipArchive;


class InscricaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $inscricoes = $user->candidato->inscricoes()->orderBy('created_at', 'desc')->get();
        $cursos = collect();

        //dd($inscricoes);
        foreach($inscricoes as $inscricao){
            $cursos->push($inscricao->curso);
        }
        return view('inscricao.index', compact('inscricoes', 'cursos'))->with(['turnos' => Curso::TURNO_ENUM, 'situacoes' => Inscricao::STATUS_ENUM, 'graus' => Curso::GRAU_ENUM]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return \Illuminate\Http\Response
     */
    public function show(Inscricao $inscricao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return \Illuminate\Http\Response
     */
    public function edit(Inscricao $inscricao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inscricao  $inscricao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Inscricao $inscricao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inscricao  $inscricao
     * @return \Illuminate\Http\Response
     */
    public function destroy(Inscricao $inscricao)
    {
        //
    }
    public function showInscricaoDocumentacao($id)
    {
        $inscricao = Inscricao::find($id);
        $this->authorize('isCandidatoDono', $inscricao);
        //$this->authorize('dataEnvio', $inscricao->chamada);
        $documentos = $this->documentosRequisitados($id);
        return view('inscricao.envio-documentos', compact('inscricao', 'documentos'));
    }

    public function showAnalisarDocumentos($sisu_id, $chamada_id, $curso_id, $inscricao_id)
    {
        $inscricao = Inscricao::find($inscricao_id);
        $this->authorize('isAdminOrAnalista', User::class);
        $documentos = $this->documentosRequisitados($inscricao_id);
        $chamada = Chamada::find($chamada_id);
        $curso = Curso::find($curso_id);
        return view('inscricao.analise-documentos', compact('inscricao', 'documentos', 'chamada', 'curso'));
    }

    public function showDocumento($inscricao_id, $documento_nome)
    {
        $inscricao = Inscricao::find($inscricao_id);
        $this->authorize('isCandidatoDonoOrAnalista', $inscricao);
        $arquivo = Arquivo::where([['inscricao_id', $inscricao_id], ['nome', $documento_nome]])->first();
        return Storage::disk()->exists($arquivo->caminho) ? response()->file(storage_path('app/'.$arquivo->caminho)) : abort(404);
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

    private function corrigirStatusInscritos($chamada_id)
    {
        $chamada = Chamada::find($chamada_id);
        $documentosAceitos = true;
        $necessitaAvaliar = false;
        foreach($chamada->inscricoes as $inscricao){
            foreach($inscricao->arquivos as $arqui){
                if(!is_null($arqui->avaliacao)){
                    if($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['recusado']){
                        $documentosAceitos = false;
                    }elseif($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['reenviado']){
                        $documentosAceitos = false;
                        $necessitaAvaliar = true;
                        break;
                    }
                }else{
                    $documentosAceitos = false;
                    $necessitaAvaliar = true;
                    break;
                }
            }
            if($documentosAceitos){
                $diferenca = array_diff($this->todosDocsRequisitados($inscricao->id)->toArray(), $inscricao->arquivos->pluck('nome')->toArray());
                if(count($diferenca) == 0){
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
                }else{
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'];
                }
            }else{
                if($necessitaAvaliar == true && $documentosAceitos == false){
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
                }else{
                    if($necessitaAvaliar == true){
                        $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
                    }else{
                        $inscricao->status = Inscricao::STATUS_ENUM['documentos_invalidados'];
                    }
                }
            }
            $inscricao->update();
        }
    }

    public function documentosRequisitados($id)
    {
        $inscricao = Inscricao::find($id);
        $documentos = collect();
        $userPolicy = new UserPolicy();
        if($userPolicy->ehAnalistaGeral(auth()->user()) || auth()->user()->role == User::ROLE_ENUM['admin'] || auth()->user()->role == User::ROLE_ENUM['candidato']){
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
        } else {
            if($userPolicy->ehAnalistaHeteroidentificacao(auth()->user())){
                if($inscricao->st_lei_etnia_p == 'S'){
                    $documentos->push('heteroidentificacao');
                    $documentos->push('fotografia');
                    if(!$documentos->contains('declaracao_cotista')){
                        $documentos->push('declaracao_cotista');
                    }
                }
            }
            if($userPolicy->ehAnalistaMedico(auth()->user())){
                if(str_contains($inscricao->no_modalidade_concorrencia, 'deficiência')){
                    $documentos->push('laudo_medico');
                    if(!$documentos->contains('declaracao_cotista')){
                        $documentos->push('declaracao_cotista');
                    }
                }
            }
        }
        return $documentos;
    }

    public function avaliarDocumento(Request $request)
    {
        $this->authorize('isAdminOrAnalista', User::class);
        if ($request->documento_id == null) {
            return redirect()->back()->withErrors(['error' => 'Envie a avaliação do documento que deseja avaliar.'])->withInput($request->all());
        }
        if($request->comentario == null && $request->aprovar == 'false') {
            return redirect()->back()->withErrors(['comentario' => 'Informe o motivo para recusar este documento.'])->withInput($request->all());
        }
        $inscricao = Inscricao::find($request->inscricao_id);
        $arquivo = Arquivo::find($request->documento_id);
        if($request->aprovar == 'true'){
            $avaliacao = Avaliacao::AVALIACAO_ENUM['aceito'];
        }elseif($request->aprovar == 'false'){
            $avaliacao = Avaliacao::AVALIACAO_ENUM['recusado'];
        }
        if($arquivo->avaliacao != null){
            $arquivoAvaliacao = $arquivo->avaliacao;
            if($request->aprovar == 'true'){
                $arquivoAvaliacao->comentario = null;
            }else{
                $arquivoAvaliacao->comentario = $request->comentario;
            }
            $arquivoAvaliacao->avaliacao = $avaliacao;
            $arquivoAvaliacao->avaliador_id = auth()->user()->id;
            $arquivoAvaliacao->update();
        }else{
            Avaliacao::create([
                'arquivo_id'  => $arquivo->id,
                'avaliacao'  =>  $avaliacao,
                'comentario' => $request->comentario,
                'avaliador_id'=> auth()->user()->id,
            ]);
        }

        $documentosAceitos = true;
        $necessitaAvaliar = false;
        foreach($inscricao->arquivos as $arqui){
            if(!is_null($arqui->avaliacao)){
                if($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['recusado']){
                    $documentosAceitos = false;
                }elseif($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['reenviado']){
                    $documentosAceitos = false;
                    $necessitaAvaliar = true;
                    break;
                }
            }else{
                $documentosAceitos = false;
                $necessitaAvaliar = true;
                break;
            }
        }
        if($documentosAceitos){
            $diferenca = array_diff($this->todosDocsRequisitados($inscricao->id)->toArray(), $inscricao->arquivos->pluck('nome')->toArray());
            if(count($diferenca) == 0){
                $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
            }else{
                $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'];
            }
        }else{
            if($necessitaAvaliar == true && $documentosAceitos == false){
                $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
            }else{
                if($necessitaAvaliar == true){
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
                }else{
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_invalidados'];
                }
            }
        }
        $inscricao->update();

        $documentosRequisitos = $this->documentosRequisitados($inscricao->id);

        foreach($documentosRequisitos as $indice => $doc){
            if($doc == $arquivo->nome){
                break;
            }
        }

        $nome = InscricaoController::getNome($arquivo->nome);
        return redirect()->back()->with(['success' => 'Documento '. $nome .' avaliado com sucesso!', 'inscricao' => $inscricao->id, 'indice' => $indice, 'nomeDoc' => $arquivo->nome]);
    }

    public function modificarComentario(Request $request)
    {
        $this->authorize('isAdmin', User::class);
        $inscricao = Inscricao::find($request->inscricao_id);
        $arquivo = Arquivo::find($request->documento_id);

        if($arquivo->avaliacao != null){
            $arquivo->avaliacao->comentario = $request->comentarioM;
            $arquivo->avaliacao->update();
        }

        $documentosRequisitos = $this->documentosRequisitados($inscricao->id);

        foreach($documentosRequisitos as $indice => $doc){
            if($doc == $arquivo->nome){
                break;
            }
        }

        $nome = InscricaoController::getNome($arquivo->nome);
        return redirect()->back()->with(['success' => 'Comentário do documento '. $nome .' modificado com sucesso!', 'inscricao' => $inscricao->id, 'indice' => $indice, 'nomeDoc' => $arquivo->nome]);
    }

    public function analisarDocumentos(Request $request)
    {
        $this->authorize('isAdminOrAnalista', User::class);
        $data = $request->all();
        if ($request->documentos == null) {
            return redirect()->back()->withErrors(['error' => 'Envie o parecer dos documentos que devem ser analisados.'])->withInput($request->all());
        }
        $inscricao = Inscricao::find($request->inscricao_id);
        foreach ($request->documentos as $documento){
            $arquivo = $inscricao->arquivos()->where('nome', $documento)->first();
            if($arquivo != null){
                if($arquivo->avaliacao != null){
                    $avaliacao = $arquivo->avaliacao;
                    if($data['analise_'.$documento] == 'aceito'){
                        $avaliacao->avaliacao = Avaliacao::AVALIACAO_ENUM['aceito'];
                    }elseif($data['analise_'.$documento] == 'recusado'){
                        $avaliacao->avaliacao = Avaliacao::AVALIACAO_ENUM['recusado'];
                    }
                    $avaliacao->comentario = $data['comentario_'.$documento];
                    $avaliacao->update();
                }else{
                    try{
                        $avaliacao = $data['analise_'.$documento];
                    }catch(Throwable $e){

                    }
                    if($avaliacao == 'aceito'){
                        $avaliacao = Avaliacao::AVALIACAO_ENUM['aceito'];
                    }elseif($avaliacao == 'recusado'){
                        $avaliacao = Avaliacao::AVALIACAO_ENUM['recusado'];
                    }
                    Avaliacao::create([
                        'arquivo_id'  => $arquivo->id,
                        'avaliacao'  =>  $avaliacao,
                        'comentario' => $data['comentario_'.$documento],
                    ]);
                }
            }
        }

        $documentosAceitos = true;
        foreach($inscricao->arquivos as $arquivo){
            if($arquivo->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['recusado']){
                $documentosAceitos = false;
                break;
            }
        }
        if($documentosAceitos){
            $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
        }else{
            $inscricao->status = Inscricao::STATUS_ENUM['documentos_pendentes'];
        }
        $inscricao->update();

        $chamada = Chamada::find($inscricao->chamada->id);
        $curso = Curso::find($request->curso_id);
        return redirect(route('chamadas.candidatos.curso', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id]))->with(['success' => 'Análise enviada com sucesso.']);
    }

    public function updateStatusEfetivado(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricaoID);

        if($request->justificativa == null && $request->efetivar == 'false'){
            return redirect()->back()->withErrors(['justificativa' => 'Informe o motivo da invalidação do cadastro.'])->withInput($request->all());
        }

        if($request->justificativa == null && $inscricao->justificativa == $request->justificativa){
            $message = "Nenhuma justificativa adicionada. ";
        }else if($request->justificativa != null){
            $message = "Justificativa adicionada. ";
        }else if(is_null($request->justificativa) && $inscricao->justificativa != null ){
            $message = "Justificativa antiga deletada. ";
        }

        if($request->justificativa != null){

            $request->validate([
                'justificativa' => ['string', 'max:500'],
            ]);

            $inscricao->justificativa = $request->justificativa;
        }else{
            $inscricao->justificativa = null;
        }

        $cotaRemanejamento = $inscricao->cotaRemanejada;
        if($cotaRemanejamento == null){
            $cota = $inscricao->cota;
        }else{
            $cota = $cotaRemanejamento;
        }
        $curso = Curso::find($request->curso);
        $cota_curso = $curso->cotas()->where('cota_id', $cota->id)->first()->pivot;
        if(($inscricao->cd_efetivado == Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'] && $request->efetivar == 'false') || (is_null($inscricao->cd_efetivado) && $request->efetivar == 'false')){
            if($inscricao->cd_efetivado == Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']){
                $cota_curso->vagas_ocupadas -= 1;
            }
            $inscricao->cd_efetivado = Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_invalidado_confirmacao'];
            $inscricao->status = Inscricao::STATUS_ENUM['documentos_invalidados'];
            $message .= "Candidato {$inscricao->candidato->no_inscrito} teve o cadastro invalidado.";
        }else if(($inscricao->cd_efetivado == Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_invalidado_confirmacao'] && $request->efetivar == 'true') || (is_null($inscricao->cd_efetivado) && $request->efetivar == 'true')) {
            /*if($inscricao->status < Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias']){
                $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
            }*/
            $cota_curso->vagas_ocupadas += 1;
            $inscricao->cd_efetivado = Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'];
            $message .= "Candidato {$inscricao->candidato->no_inscrito} teve o cadastro validado.";
        }else if($inscricao->cd_efetivado == Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_invalidado'] && $request->efetivar == 'true'){
            $cota_curso->vagas_ocupadas += 1;
            $inscricao->cd_efetivado = Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'];
            $message .= "Candidato {$inscricao->candidato->no_inscrito} teve o cadastro validado.";
        }
        $inscricao->update();
        $cota_curso->update();

        return redirect()->back()->with(['success' => $message]);
    }

    public function bloquearInscricao(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricaoID);

        if($inscricao->retificacao == null){
            $message = "Inscrição bloqueada para retificação";
            $inscricao->retificacao = intval($request->bloquear);
        }else{
            if($request->bloquear == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial'] && $inscricao->retificacao == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial']){
                $inscricao->retificacao = null;
                $message = "Inscrição desbloqueada para retificação.";
            }elseif($request->bloquear == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico'] && $inscricao->retificacao == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico']){
                $inscricao->retificacao = null;
                $message = "Inscrição desbloqueada para retificação.";
            }else{
                if($inscricao->retificacao == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial_e_medico']){
                    if($request->bloquear == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial']){
                        $inscricao->retificacao = Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico'];
                    }else{
                        $inscricao->retificacao = Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial'];
                    }
                    $message = "Inscrição desbloqueada para retificação.";
                }else{
                    if($request->bloquear == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial']){
                        if($inscricao->retificacao == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico']){
                            $inscricao->retificacao = Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial_e_medico'];
                        }else{
                            $inscricao->retificacao = Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial'];
                        }
                    }else{
                        if($inscricao->retificacao == Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial']){
                            $inscricao->retificacao = Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_racial_e_medico'];
                        }else{
                            $inscricao->retificacao = Inscricao::STATUS_RETIFICACAO['bloqueado_motivo_medico'];
                        }
                    }
                    $message = "Inscrição bloqueada para retificação";
                }
            }
        }
        $inscricao->update();
        return redirect()->back()->with(['success' => $message]);
    }

    public function inscricaoDocumentoAjax(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricao_id);
        $this->authorize('isAdminOrAnalista', User::class);
        $arquivo = Arquivo::where([['inscricao_id', $request->inscricao_id], ['nome', $request->documento_nome]])->first();
        $userPolicy = new UserPolicy();
        if($arquivo != null){
            if($arquivo->avaliacao != null){
                $documento = [
                    'id' => $arquivo->id,
                    'caminho' => route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $request->documento_nome]),
                    'avaliacao' => $arquivo->avaliacao->avaliacao,
                    'comentario' => $arquivo->avaliacao->comentario,
                    'analisaGeral' => $userPolicy->ehAnalistaGeral(auth()->user()),
                    'admin' => $userPolicy->isAdmin(auth()->user()),
                ];
                if($arquivo->avaliacao->avaliador != null){
                    $documento['avaliador'] = "Avaliado por: ".$arquivo->avaliacao->avaliador->name;
                }else{
                    $documento['avaliador'] = null;
                }
            }else{
                $documento = [
                    'id' => $arquivo->id,
                    'caminho' => route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $request->documento_nome]),
                    'avaliacao' => null,
                    'comentario' => null,
                    'analisaGeral' => $userPolicy->ehAnalistaGeral(auth()->user()),
                    'admin' => $userPolicy->isAdmin(auth()->user()),
                ];
                    $documento['avaliador'] = null;
            }
        }else{
            $documento = [
                'id' => null,
                'nome' => $this->getCaixaTexto($inscricao, $request->documento_nome),
            ];
        }


        return response()->json($documento);
    }
    public function inscricaoProxDocumentoAjax(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricao_id);
        $this->authorize('isAdminOrAnalista', User::class);
        $documentos = $this->documentosRequisitados($inscricao->id);
        $indiceProx = $request->documento_indice;

        if($indiceProx >= 0 && $indiceProx < count($documentos)){
            $documento = [
                'nome' => $documentos[$indiceProx],
                'indice' => $indiceProx,
            ];
        }elseif($indiceProx < -1){
            $documento = [
                'nome' => $documentos[count($documentos)-1],
                'indice' => count($documentos)-1,
            ];
        }else{
            $documento = [
                'nome' => 'ficha',
                'indice' => -1,
            ];
        }
        return response()->json($documento);
    }

    public function downloadDocumentosCandidato($id)
    {
        $inscricao = Inscricao::find($id);
        $this->authorize('isAdminOrAnalista', User::class);

        if(is_null($inscricao->arquivos->first())){
            return redirect()->back()->withErrors(['error' => 'Não há documentos para download.']);
        }
        $nomeCandidato = $inscricao->candidato->no_inscrito;

        $filename = $nomeCandidato.'.zip';
        $zip = new ZipArchive();
        $zip->open(storage_path('app'. DIRECTORY_SEPARATOR . $filename), ZipArchive::CREATE);
        $path = 'app'. DIRECTORY_SEPARATOR . 'documentos' . DIRECTORY_SEPARATOR . 'inscricaos' . DIRECTORY_SEPARATOR . $id;


        $files = File::files(storage_path($path));
        foreach($files as $file){
            if (!$file->isDir()) {
                $relativeName = basename($file);
                $zip->addFile($file, $relativeName);
            }
        }
        $zip->close();
        //return response()->download(storage_path('app'. DIRECTORY_SEPARATOR . $filename));
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename(storage_path('app'. DIRECTORY_SEPARATOR . $filename)).'"');
        header("Content-length: " . filesize(storage_path('app'. DIRECTORY_SEPARATOR . $filename)));
        header("Pragma: no-cache");
        header("Expires: 0");

        ob_clean();
        flush();

        readfile(storage_path('app'. DIRECTORY_SEPARATOR . $filename));

        ignore_user_abort(true);
        unlink(storage_path('app'. DIRECTORY_SEPARATOR . $filename));
        exit();
    }

    private static function getCaixaTexto($inscricao, $documento){
        if($inscricao->status != Inscricao::STATUS_ENUM['documentos_pendentes']){
            switch($documento){
                case 'historico':
                    return "Comprometo-me a entregar junto ao DRCA/UFAPE o Histórico Escolar do Ensino Médio ou Equivalente, na
                    primeira semana de aula.";
                case 'nascimento_ou_casamento':
                    return "Comprometo-me a entregar junto ao DRCA/UFAPE o Registro de Nascimento ou Certidão de Casamento, na
                    primeira semana de aula.";
                case 'quitacao_eleitoral':
                    return "Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Eleitoral, na
                    primeira semana de aula.";
                case 'quitacao_militar':
                    return "Comprometo-me a entregar junto ao DRCA/UFAPE o Comprovante de quitação com o Serviço Militar, na
                    primeira semana de aula.";
            }
        }else{
            return "Aguardando o envio do documento.";
        }
    }

    public static function getNome($documento){
        if($documento == 'certificado_conclusao'){
            return "Certificado de Conclusão do Ensino Médio";
        }else if($documento == 'historico'){
            return "Histórico Escolar do Ensino Médio ou equivalente";
        }else if($documento == 'nascimento_ou_casamento'){
            return "Registro de Nascimento ou Certidão de Casamento";
        }else if($documento == 'cpf'){
            return "Cadastro de Pessoa Física (CPF)";
        }else if($documento == 'rg'){
            return "Carteira de Identidade (RG)";
        }else if($documento == 'quitacao_eleitoral'){
            return "Comprovante de quitação com o Serviço Eleitoral";
        }else if($documento == 'quitacao_militar'){
            return "Comprovante de quitação com o Serviço Militar";
        }else if($documento == 'foto'){
            return "Foto 3x4";
        }else if($documento == 'autodeclaracao'){
            return "Autodeclaração de cor/etnia";
        }else if($documento == 'comprovante_renda'){
            return "Comprovante de renda";
        }else if($documento == 'laudo_medico'){
            return "Laudo médico e exames";
        }else if($documento == 'declaracao_veracidade'){
            return "Declaração de Veracidade";
        }else if($documento == 'rani'){
            return "Declaração Indígena";
        }else if($documento == 'heteroidentificacao'){
            return "Vídeo de Heteroidentificação";
        }else if($documento == 'fotografia'){
            return "Foto de Heteroidentificação";
        }else if($documento == 'declaracao_cotista'){
            return "Declaração Cotista";
        }else if($documento == 'ficha'){
            return "Ficha Geral";
        }
    }

    public function confirmarInvalidacao(Request $request)
    {
        $this->authorize('isAdmin', User::class);
        $inscricao = Inscricao::find($request->inscricaoID);
        if($request->confirmarInvalidacao == 'false'){
            $inscricao->cd_efetivado = null;
            //
            $documentosAceitos = true;
            $necessitaAvaliar = false;
            foreach($inscricao->arquivos as $arqui){
                if(!is_null($arqui->avaliacao)){
                    if($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['recusado']){
                        $documentosAceitos = false;
                    }
                }else{
                    $documentosAceitos = false;
                    $necessitaAvaliar = true;
                    break;
                }
            }
            if($documentosAceitos){
                $diferenca = array_diff($this->documentosRequisitados($inscricao->id)->toArray(), $inscricao->arquivos->pluck('nome')->toArray());
                if(count($diferenca) == 0){
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
                }else{
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias'];
                }
            }else{
                if($necessitaAvaliar == true && $documentosAceitos == false){
                    $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
                }else{
                    if($necessitaAvaliar == true){
                        $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
                    }else{
                        $inscricao->status = Inscricao::STATUS_ENUM['documentos_invalidados'];
                    }
                }
            }
            $inscricao->update();

            $message = 'O candidato teve a invalidação do cadastro desfeita. É necessário reavaliar os documentos invalidados.';
        }else{
            $inscricao->cd_efetivado = Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_invalidado'];
            $message = 'O candidato teve a invalidação do cadastro confirmada.';
        }
        $inscricao->update();
        return redirect()->back()->with(['success' => $message]);
    }
}
