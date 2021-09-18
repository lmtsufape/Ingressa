<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Http\Requests\CursoRequest;

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
        return view('curso.index', compact('cursos'))->with(['turnos' => Curso::TURNO_ENUM]);
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
        $curso->save();

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
        $curso->update();

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
}
