<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Avaliacao;
use App\Models\Chamada;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\User;
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
        $inscricoes = $user->candidato->inscricoes;
        $cursos = collect();

        //dd($inscricoes);
        foreach($inscricoes as $inscricao){
            $cursos->push($inscricao->curso);
        }
        return view('inscricao.index', compact('inscricoes', 'cursos'))->with(['turnos' => Curso::TURNO_ENUM, 'situacoes' => Inscricao::STATUS_ENUM]);
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
        $this->authorize('dataEnvio', $inscricao->chamada);
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

    public function enviarDocumentos(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricao_id);
        $this->authorize('isCandidatoDono', $inscricao);
        if ($request->documentos == null) {
            return redirect()->back()->withErrors(['error' => 'Anexe os documentos que devem ser enviados.'])->withInput($request->all());
        }

        foreach($request->documentos as $documento){
            if($request[$documento]!=null){
                $arquivo = Arquivo::where([['inscricao_id', $inscricao->id], ['nome', $documento]])->first();
                if($arquivo != null){
                    if (Storage::disk()->exists('public/' . $arquivo->caminho)) {
                        Storage::delete('public/' . $arquivo->caminho);
                    }

                    $novoArquivo = $request[$documento];
                    $path = 'documentos/inscricaos/'. $inscricao->id .'/';
                    $nome = $novoArquivo->getClientOriginalName();
                    Storage::putFileAs('public/'.$path, $novoArquivo, $nome);

                    $arquivo->caminho = $path . $nome;
                    $arquivo->update();
                    $arquivo->avaliacao->comentario = null;
                    $arquivo->avaliacao->avaliacao = Avaliacao::AVALIACAO_ENUM['reenviado'];
                    $arquivo->avaliacao->update();

                }else{
                    $novoArquivo = $request[$documento];
                    $path = 'documentos/inscricaos/'. $inscricao->id .'/';
                    $nome = $novoArquivo->getClientOriginalName();
                    Storage::putFileAs('public/'.$path, $novoArquivo, $nome);

                    Arquivo::create([
                        'inscricao_id'  => $inscricao->id,
                        'caminho'  => $path . $nome,
                        'nome' => $documento,
                    ]);
                }
            }
        }
        $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
        $inscricao->update();
        return redirect(route('inscricaos.index'))->with(['success' => 'Documentação enviada com sucesso. Aguarde o resultado da avaliação dos documentos.']);
    }

    public function showDocumento($inscricao_id, $documento_nome)
    {
        $inscricao = Inscricao::find($inscricao_id);
        $this->authorize('isCandidatoDono', $inscricao);
        $arquivo = Arquivo::where([['inscricao_id', $inscricao_id], ['nome', $documento_nome]])->first();
        return Storage::disk()->exists('public/' . $arquivo->caminho) ? response()->file('storage/' . $arquivo->caminho) : abort(404);
    }

    private function documentosRequisitados($id)
    {
        $inscricao = Inscricao::find($id);
        $documentos = collect();
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
        if($inscricao->st_lei_etnia_p == 'S'){
            $documentos->push('autodeclaracao');
        }
        if($inscricao->st_lei_renda == 'S'){
            $documentos->push('comprovante_renda');
        }
        if(str_contains($inscricao->no_modalidade_concorrencia, 'deficiência')){
            $documentos->push('laudo_medico');
        }
        return $documentos;
    }

    public function avaliarDocumento(Request $request)
    {
        $this->authorize('isAdminOrAnalista', User::class);
        if ($request->documento_id == null) {
            return redirect()->back()->withErrors(['error' => 'Envie a avaliação do documento que deseja avaliar.'])->withInput($request->all());
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
            $arquivoAvaliacao->update();
        }else{
            Avaliacao::create([
                'arquivo_id'  => $arquivo->id,
                'avaliacao'  =>  $avaliacao,
                'comentario' => $request->comentario,
            ]);
        }

        $documentosAceitos = true;
        foreach($inscricao->arquivos as $arqui){
            if($arqui->avaliacao != null){
                if($arqui->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['recusado']){
                    $documentosAceitos = false;
                    break;
                }
            }else{
                $documentosAceitos = false;
                break;
            }
        }
        if($documentosAceitos){
            $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos'];
        }else{
            $inscricao->status = Inscricao::STATUS_ENUM['documentos_requeridos'];
        }
        $inscricao->update();

        $documentosRequisitos = $this->documentosRequisitados($inscricao->id);

        foreach($documentosRequisitos as $indice => $doc){
            if($doc == $arquivo->nome){
                break;
            }
        }

        return redirect()->back()->with(['success' => 'Documento '. $this->getNome($arquivo->nome) .' avaliado com sucesso!', 'inscricao' => $inscricao->id, 'indice' => $indice, 'nomeDoc' => $arquivo->nome]);
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
            $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos'];
        }else{
            $inscricao->status = Inscricao::STATUS_ENUM['documentos_requeridos'];
        }
        $inscricao->update();

        $chamada = Chamada::find($inscricao->chamada->id);
        $curso = Curso::find($request->curso_id);
        return redirect(route('chamadas.candidatos.curso', ['sisu_id' => $chamada->sisu->id, 'chamada_id' => $chamada->id, 'curso_id' => $curso->id]))->with(['success' => 'Análise enviada com sucesso.']);
    }

    public function updateStatusEfetivado(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricaoID);

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
        if($inscricao->cd_efetivado == true && $request->efetivar == 'false'){
            $cota_curso->vagas_ocupadas -= 1;
            $inscricao->cd_efetivado = false;
            $message .= "Candidato {$inscricao->candidato->user->name} teve o cadastro invalidado.";
        }else if($inscricao->cd_efetivado == false && $request->efetivar == 'true') {
            if($inscricao->status < Inscricao::STATUS_ENUM['documentos_aceitos']){
                $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos'];
            }
            $cota_curso->vagas_ocupadas += 1;
            $inscricao->cd_efetivado = true;
            $message .= "Candidato {$inscricao->candidato->user->name} teve o cadastro validado.";
        }
        $inscricao->update();
        $cota_curso->update();

        return redirect()->back()->with(['success' => $message]);
    }

    public function inscricaoDocumentoAjax(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricao_id);
        $this->authorize('isCandidatoDono', $inscricao);
        $arquivo = Arquivo::where([['inscricao_id', $request->inscricao_id], ['nome', $request->documento_nome]])->first();
        if($arquivo != null){
            if($arquivo->avaliacao != null){
                $documento = [
                    'id' => $arquivo->id,
                    'caminho' => route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $request->documento_nome]),
                    'avaliacao' => $arquivo->avaliacao->avaliacao,
                    'comentario' => $arquivo->avaliacao->comentario,
                ];
            }else{
                $documento = [
                    'id' => $arquivo->id,
                    'caminho' => route('inscricao.arquivo', ['inscricao_id' => $inscricao->id, 'documento_nome' => $request->documento_nome]),
                    'avaliacao' => null,
                    'comentario' => null,
                ];
            }
        }else{
            $documento = [
                'id' => null,
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
            ];
        }else{
            $documento = [
                'nome' => 'ficha',
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
        $nomeCandidato = $inscricao->candidato->user->name;

        $filename = $nomeCandidato.'.zip';
        $zip = new ZipArchive();
        $zip->open(storage_path('app'. DIRECTORY_SEPARATOR . $filename), ZipArchive::CREATE);
        $path = 'app'. DIRECTORY_SEPARATOR .'public' . DIRECTORY_SEPARATOR . 'documentos' . DIRECTORY_SEPARATOR . 'inscricaos' . DIRECTORY_SEPARATOR . $id;


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

    private function getNome($documento){
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
            return "Laudo médico";
        }else if($documento == 'ficha'){
            return "Ficha Geral";
        }
    }
}
