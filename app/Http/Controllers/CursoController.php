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
        $curso = Curso::find($id);
        $curso->delete();

        return redirect(route('cursos.index'))->with(['success' => 'Curso deletado com sucesso!']);
    }
}
