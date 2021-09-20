<?php

namespace App\Http\Controllers;

use App\Http\Requests\SisuRequest;
use App\Models\Chamada;
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
        $this->authorize('isAdminOrAnalista', User::class);
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
        $chamadas = Chamada::where('sisu_id', '=', $sisu->id)->orderBy('created_at', 'ASC')->get();
        return view('sisu.show', compact('sisu', 'chamadas'));
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
}
