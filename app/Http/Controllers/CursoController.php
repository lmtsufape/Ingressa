<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Http\Requests\CursoRequest;
use App\Models\Chamada;
use App\Models\Inscricao;
use Illuminate\Support\Facades\File;
use ZipArchive;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', User::class);
        $cursos = Curso::orderBy('nome')->get();
        return view('curso.index', compact('cursos'))->with(['turnos' => Curso::TURNO_ENUM, 'graus' => Curso::GRAU_ENUM]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('isAdmin', User::class);
        return view('curso.create')->with(['turnos' => Curso::TURNO_ENUM]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CursoRequest $request)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $curso =  new Curso();
        $curso->setAtributes($request);

        return redirect(route('cursos.index'))->with(['success' => 'Curso criado com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('isAdmin', User::class);
        $curso = Curso::find($id);
        return view('curso.edit')->with(['curso' => $curso, 'turnos' => Curso::TURNO_ENUM]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CursoRequest $request, $id)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $curso = Curso::find($id);
        $curso->setAtributes($request);

        return redirect(route('cursos.index'))->with(['success' => 'Curso atualizado com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin', User::class);
        $curso = Curso::find($id);
        $this->desvincularCotas($curso);
        $curso->delete();

        return redirect(route('cursos.index'))->with(['success' => 'Curso deletado com sucesso!']);
    }

    /**
     * Desvincula todos as cotas do curso passado.
     *
     * @param  App\Models\Curso  $curso
     * @return void
     */
    private function desvincularCotas(Curso $curso)
    {
        $this->authorize('isAdmin', User::class);
        foreach ($curso->cotas as $cota) {
            $cota->cursos()->detach($curso->id);
        }
    }

    /**
     * Atualizar dados de um curso que foi preenchido pelo ajax.
     *
     * @param  \App\Http\Requests\CursoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAjax(CursoRequest $request)
    {
        $request->validate([
            'curso' => 'required',
        ]);
        
        $curso = Curso::find($request->curso);
        $curso->setAtributes($request);
        
        return redirect(route('cursos.index'))->with(['success' => 'Curso atualizado com sucesso!']);
    }

    /**
     * Retorna um json com as informações do curso passado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function infoCurso(Request $request)
    {
        $curso = Curso::find($request->curso_id);
        
        return response()->json($curso);
    }

    public function downloadDocumentosTodosCandidatos($curso, $chamada)
    {
        $this->authorize('isAdmin', User::class);
        $curso = Curso::find($curso);
        $chamada = Chamada::find($chamada);
        $inscricoes = Inscricao::where([['curso_id', $curso->id], ['chamada_id', $chamada->id]])->get();
        $filename = 'Documentos dos Candidatos('.$curso->nome.' - '.$curso->getTurno().') .zip';
        $zip = new ZipArchive();
        $zip->open(storage_path('app'. DIRECTORY_SEPARATOR . $filename), ZipArchive::CREATE);

        $temArquivo = false;

        foreach($inscricoes as $inscricao){
            $arquivos = $inscricao->arquivos;
            if($arquivos->first() != null){
                $temArquivo = true;
                $nomeCandidato = $inscricao->candidato->no_inscrito . ' - ' . $inscricao->co_inscricao_enem;
                $path = 'app'. DIRECTORY_SEPARATOR . 'documentos' . DIRECTORY_SEPARATOR . 'inscricaos' . DIRECTORY_SEPARATOR . $inscricao->id;

                $zip->addEmptyDir($nomeCandidato);
                $files = File::files(storage_path($path));
                foreach($files as $file){
                    if (!$file->isDir()) {
                        $relativeName = basename($file);
                        $zip->addFile($file, $nomeCandidato.'/'.$relativeName);
                    }
                }
            }
        }

        $zip->close();
        if(!$temArquivo){
            return redirect()->back()->with(['error' => 'Não há documentos enviados ainda.']);
        }
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
}
