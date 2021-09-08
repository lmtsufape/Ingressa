<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadaRequest;
use App\Models\Chamada;
use App\Models\Sisu;
use Illuminate\Http\Request;

class ChamadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $sisu = Sisu::find($id);
        $tem_regular = Chamada::where([['sisu_id', $id], ['regular', true]])->first();
        return view('chamada.create', compact('sisu', 'tem_regular'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChamadaRequest $request)
    {
        $request->validated();
        $chamada = new Chamada();
        $chamada->setAtributes($request);

        $chamada->sisu_id = $request->sisu;

        if($request->regular == "true"){
            $chamada->regular = true;
        }else{
            $chamada->regular = false;
        }
        $chamada->save();

        return redirect(route('sisus.show', ['sisu' => $request->sisu]))->with(['success' => 'Chamada cadastrada com sucesso!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Http\Response
     */
    public function show(Chamada $chamada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chamada = Chamada::find($id);
        $tem_regular = (Chamada::where([['sisu_id', $chamada->sisu->id], ['regular', true]])->first()) != null;
        return view('chamada.edit', compact('chamada', 'tem_regular'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Http\Response
     */
    public function update(ChamadaRequest $request, $id)
    {
        $request->validated();
        $chamada = Chamada::find($id);
        $chamada->setAtributes($request);

        if($request->regular == "true"){
            $chamada->regular = true;
        }else{
            $chamada->regular = false;
        }
        $chamada->update();

        return redirect(route('sisus.show', ['sisu' => $chamada->sisu]))->with(['success' => 'Chamada editada com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $chamada = Chamada::find($id);
        $sisu = Sisu::find($chamada->sisu_id);
        $chamada->delete();

        return redirect(route('sisus.show', ['sisu' => $sisu->id]))->with(['success' => 'Chamada deletada com sucesso!']);
    }
}
