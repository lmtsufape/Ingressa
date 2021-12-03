<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataChamadaRequest;
use App\Models\DataChamada;
use App\Models\Chamada;
use Illuminate\Http\Request;

class DataChamadaController extends Controller
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
    public function store(DataChamadaRequest $request)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();

        $resultado = $this->checar_tipo($request);
        if ($resultado) {
            return $resultado;
        }

        $data = new DataChamada();
        $data->setAtributes($request);

        $data->chamada_id = $request->chamada;
        $data->save();

        return redirect()->back()->with(['success_data' => 'Data criada com sucesso']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DataChamada  $dataChamada
     * @return \Illuminate\Http\Response
     */
    public function show(DataChamada $dataChamada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DataChamada  $dataChamada
     * @return \Illuminate\Http\Response
     */
    public function edit(DataChamada $dataChamada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DataChamada  $dataChamada
     * @return \Illuminate\Http\Response
     */
    public function update(DataChamadaRequest $request, $id)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $dataChamada = DataChamada::find($id);
        $dataChamada->setAtributes($request);

        $dataChamada->update();

        return redirect()->back()->with(['success_data' => 'Data editada com sucesso']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DataChamada  $dataChamada
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin', User::class);
        $dataChamada = DataChamada::find($id);
        $dataChamada->delete();

        return redirect()->back()->with(['success_data' => 'Data deletada com sucesso.']);
    }

    private function checar_tipo(Request $request) 
    {
        $chamada = Chamada::find($request->chamada);
        $data = $chamada->datasChamada()->where('tipo', $request->tipo)->first();
        if ($data == null) {
            return false;
        }
        return redirect()->back()->withErrors(['tipo' => 'Esse tipo de data já existe.'])->withInput($request->all());
    }
}
