<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cota;
use App\Models\Curso;
use App\Http\Requests\CotaRequest;

class CotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cotas = Cota::orderBy('nome')->get();
        return view('cota.index', compact('cotas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();
        return view('cota.create', compact('cursos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CotaRequest $request)
    {
        $request->validated();
        $validated = $this->validarOpcionalObrigatorio($request);
        if ($validated != null) {
            return $validated;
        }

        $cota = new Cota();
        $cota->setAtributes($request);
        $cota->save();
        $this->vincularCursos($request, $cota, 'create');
        
        return redirect(route('cota.index'))->with(['success' => 'Cota criada com sucesso!']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Checa se um checkbox foi marcado mais faltou o preenchimento do campo da porcetagem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validarOpcionalObrigatorio(CotaRequest $request)
    {
        foreach ($request->cursos as $i => $valor) {
            if ($valor != null && $request->percentual[$i] == null) {
                return redirect()->back()->withErrors(['percentual.'.$i => 'O campo de porcetagem é obrigatório caso o curso que esteja marcado.'])->withInput($request->all());
            }
        }
    }

    /**
     * Vincula as cotas aos concursos com as porcentagens passadas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Models\Cota  $cota
     * @param  String  $metodo
     * @return void
     */
    private function vincularCursos(CotaRequest $request, Cota $cota, $metodo)
    {
        if ($metodo == "create") {
            foreach ($request->cursos as $i => $curso_id) {
                if ($curso_id != null) {
                    $curso = Curso::find($curso_id);
                    $curso->cotas()->attach($cota->id, ['percentual_cota' => $request->percentual[$i]]);
                }
            }
        }
    }
}
