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
use Illuminate\Support\Facades\Storage;
use Throwable;

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
            $arquivoAvaliacao->avaliacao = $avaliacao;
            $arquivoAvaliacao->comentario = $request->comentario;
            $arquivoAvaliacao->update();
        }else{
            Avaliacao::create([
                'arquivo_id'  => $arquivo->id,
                'avaliacao'  =>  $avaliacao,
                'comentario' => $request->comentario,
            ]);
        }

        $documentosAceitos = true;
        foreach($inscricao->arquivos as $arquivo){
            if($arquivo->avaliacao != null){
                if($arquivo->avaliacao->avaliacao == Avaliacao::AVALIACAO_ENUM['recusado']){
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

        return redirect()->back()->with(['success' => 'Documento avaliado com sucesso!']);
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
        $cotaRemanejamento = $inscricao->cotaRemanejada;
        if($cotaRemanejamento == null){
            $cota = $inscricao->cota;
        }else{
            $cota = $cotaRemanejamento;
        }
        $curso = Curso::find($request->curso);
        $cota_curso = $curso->cotas()->where('cota_id', $cota->id)->first()->pivot;
        if($inscricao->cd_efetivado==true){
            $cota_curso->vagas_ocupadas -= 1;
            $inscricao->cd_efetivado = false;
            $message = "Candidato {$inscricao->candidato->user->name} teve a inscrição não efetivada";
        }else {
            if($inscricao->status < Inscricao::STATUS_ENUM['documentos_aceitos']){
                $inscricao->status = Inscricao::STATUS_ENUM['documentos_aceitos'];
            }
            $cota_curso->vagas_ocupadas += 1;
            $inscricao->cd_efetivado = true;
            $message = "Candidato {$inscricao->candidato->user->name} teve a inscrição efetivada";
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
}
