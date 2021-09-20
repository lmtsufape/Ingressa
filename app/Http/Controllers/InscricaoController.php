<?php

namespace App\Http\Controllers;

use App\Models\Arquivo;
use App\Models\Curso;
use App\Models\Inscricao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            if($inscricao->ds_turno == 'Matutino'){
                $turno = Curso::TURNO_ENUM['matutino'];
            }elseif($inscricao->ds_turno == 'Vespertino'){
                $turno = Curso::TURNO_ENUM['vespertino'];
            }elseif($inscricao->ds_turno == 'Integral'){
                $turno = Curso::TURNO_ENUM['integral'];
            }elseif($inscricao->ds_turno == 'Noturno'){
                $turno = Curso::TURNO_ENUM['noturno'];
            }
            $cursos->push(Curso::where([['cod_curso', $inscricao->co_ies_curso], ['turno', $turno]])->first());
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
        $documentos = $this->documentosRequisitados($id);
        //$documentos = $inscricao->arquivos;
        /*if(auth()->user()->role == User::ROLE_ENUM['analista']){
            return view('requerimento.analise-documentos', compact('inscricao', 'documentos'));
        }*/
        return view('inscricao.envio-documentos', compact('inscricao', 'documentos'));
    }

    public function enviarDocumentos(Request $request)
    {
        $inscricao = Inscricao::find($request->inscricao_id);
        $documentosRequisitados = $this->documentosRequisitados($request->inscricao_id);
        $quantidadeDocumentos = count($documentosRequisitados);

        if(!in_array('cpf', $request->documentos)){
            $quantidadeDocumentos -= 1;
        }

        if ($request->documentos == null || $quantidadeDocumentos != count($request->documentos)) {
            return redirect()->back()->withErrors(['error' => 'Anexe os documentos que devem ser enviados.'])->withInput($request->all());
        }

        foreach($request->documentos as $documento){
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
                $documento->update();

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
        $inscricao->status = Inscricao::STATUS_ENUM['documentos_enviados'];
        $inscricao->update();
        return redirect(route('inscricaos.index'))->with(['success' => 'Documentação enviada com sucesso. Aguarde o resultado da avaliação dos documentos.']);
    }

    public function showDocumento($inscricao_id, $documento_nome)
    {
        $inscricao = Inscricao::find($inscricao_id);
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
}
