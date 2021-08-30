<?php

namespace App\Http\Controllers;

use App\Http\Requests\SisuRequest;
use App\Models\Sisu;
use Illuminate\Http\Request;

class SisuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $sisu = Sisu::find($id);
        $chamadas = $sisu->chamadas;
        return view('sisu.show', compact('sisu', 'chamadas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sisu  $sisu
     * @return \Illuminate\Http\Response
     */
    public function edit(Sisu $sisu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sisu  $sisu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sisu $sisu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sisu  $sisu
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sisu = Sisu::find($id);
        $sisu->delete();

        return redirect(route('sisus.index'))->with(['success' => 'Edição deletada com sucesso!']);
    }
}
