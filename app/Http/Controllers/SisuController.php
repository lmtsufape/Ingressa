<?php

namespace App\Http\Controllers;

use App\Http\Requests\SisuRequest;
use App\Models\Chamada;
use App\Models\Curso;
use App\Models\Sisu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class SisuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', User::class);
        $sisus = Sisu::orderBy('edicao', 'DESC')->get();
        return view('sisu.index', compact('sisus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('isAdmin', User::class);
        return view('sisu.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SisuRequest $request)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $sisu = new Sisu();
        $sisu->setAtributes($request);
        $sisu->save();

        return redirect(route('sisus.index'))->with(['success' => 'Edição cadastrada com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sisu  $sisu
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('isAdminOrAnalista', User::class);
        $sisu = Sisu::find($id);
        $chamadas = Chamada::where('sisu_id', '=', $sisu->id)->orderBy('created_at', 'DESC')->get();

        $batches = collect();
        foreach($chamadas as $chamada){
            if($chamada->job_batch_id != null){
                $batches->add(Bus::findBatch($chamada->job_batch_id));
            }else{
                $batches->add(null);
            }
        }
        $cursos = Curso::orderBy('nome')->get();
        $turnos = Curso::TURNO_ENUM;
        $tem_regular = Chamada::where([['sisu_id', $id], ['regular', true]])->first();
        return view('sisu.show', compact('sisu', 'chamadas', 'batches', 'cursos', 'turnos', 'tem_regular'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sisu  $sisu
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('isAdmin', User::class);
        $sisu = Sisu::find($id);
        return view('sisu.edit', compact('sisu'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sisu  $sisu
     * @return \Illuminate\Http\Response
     */
    public function update(SisuRequest $request, $id)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $sisu = Sisu::find($id);
        $sisu->setAtributes($request);
        $sisu->update();

        return redirect(route('sisus.index'))->with(['success' => 'Edição editada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sisu  $sisu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin', User::class);
        $sisu = Sisu::find($id);
        $sisu->delete();

        return redirect(route('sisus.index'))->with(['success' => 'Edição deletada com sucesso!']);
    }
    
    public function importarPlanilhasRegular(Request $request, $sisu_id)
    {
        $this->authorize('isAdmin', User::class);
        $sisu = Sisu::find($sisu_id);

        $sisu->salvar_import_regular($request->arquivoRegular);
        
        return redirect(route('sisus.index'))->with(['success' => 'Arquivo da lista regular importado com sucesso!']);
    }

    public function importarPlanilhasEspera(Request $request, $sisu_id)
    {
        $this->authorize('isAdmin', User::class);
        $sisu = Sisu::find($sisu_id);

        $sisu->salvar_import_espera($request->arquivoEspera);

        return redirect(route('sisus.index'))->with(['success' => 'Arquivo das listas de espera importado com sucesso!']);
    }
}
