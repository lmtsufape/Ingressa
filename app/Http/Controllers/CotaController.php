<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Remanejamento;
use App\Http\Requests\CotaRequest;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('isAdmin', User::class);
        $cotas = Cota::orderBy('id')->get();
        $cursos = Curso::orderBy('nome')->get();
        $turnos = Curso::TURNO_ENUM;
        return view('cota.index', compact('cotas', 'cursos', 'turnos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('isAdmin', User::class);
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
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $validated = $this->validarOpcionalObrigatorio($request);
        if ($validated != null) {
            return $validated;
        }

        $cota = new Cota();
        $cota->setAtributes($request);
        $cota->save();
        $this->vincularCursos($request, $cota);

        return redirect(route('cotas.index'))->with(['success' => 'Cota criada com sucesso!']);
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
        $cota = Cota::find($id);
        $cursos = Curso::orderBy('nome')->get();
        return view('cota.edit', compact('cota', 'cursos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CotaRequest $request, $id)
    {
        $this->authorize('isAdmin', User::class);
        $cota = Cota::find($id);
        $request->validated();
        $validated = $this->validarOpcionalObrigatorio($request);
        if ($validated != null) {
            return $validated;
        }

        $cota->setAtributes($request);
        $cota->update();
        $this->desvincularCursos($cota);
        $this->vincularCursos($request, $cota);

        return redirect(route('cotas.index'))->with(['success' => 'Cota atualizada com sucesso!']);
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
        $cota = Cota::find($id);
        $this->desvincularCursos($cota);
        $cota->delete();

        return redirect(route('cotas.index'))->with(['success' => 'Cota deletada com sucesso!']);
    }

    /**
     * View de remanejamento para uma cota passada.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remanejamento($id)
    {
        $this->authorize('isAdmin', User::class);
        $cota = Cota::find($id);
        $cotas = Cota::where('id', '!=', $id)->orderBy('nome')->get();
        return view('cota.remanejamento', compact('cota', 'cotas'));
    }

    /**
     * Salvar ordem do remanejamento de cotas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function remanejamentoUpdate(Request $request, $id)
    {
        $this->authorize('isAdmin', User::class);
        $cota = Cota::find($id);

        try {
            $this->vincularOrdem($request, $cota);
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') {
                return redirect()->back()->withErrors(['error' => 'Há duplicações na ordem de remanejamento!']);
            }
            throw $e;
        }

        return redirect(route('cotas.index'))->with(['success' => 'Ordem de remanejamento salva com sucesso!']);
    }

    /**
     * Vincula a ordem das cotas de remanejamento para uma cota passada.
     *
     * @param  App\Models\Cota  $cota
     * @return void
     */
    private function vincularOrdem(Request $request, Cota $cota)
    {
        $this->authorize('isAdmin', User::class);
        DB::transaction(function () use ($cota, $request) {
            Remanejamento::where('cota_id', $cota->id)->delete();

            foreach ($request->cotas as $i => $proxCota) {
                if ($proxCota != null) {
                    Remanejamento::Create(['ordem' => $i + 1, 'cota_id' => $cota->id, 'id_prox_cota' => $proxCota]);
                }
            }
        });
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
            if ($valor != null && $request->quantidade[$i] == null) {
                return redirect()->back()->withErrors(['quantidade.' . $i => 'O campo de quantidade é obrigatório caso o curso esteja marcado.'])->withInput($request->all());
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
    private function vincularCursos(CotaRequest $request, Cota $cota)
    {
        $this->authorize('isAdmin', User::class);
        foreach ($request->cursos as $i => $curso_id) {
            if ($curso_id != null) {
                $curso = Curso::find($curso_id);
                $curso->cotas()->attach($cota->id, ['quantidade_vagas' => $request->quantidade[$i], 'vagas_ocupadas' => 0]);
            }
        }
    }

    /**
     * Desvincula todos os cursos da cota passada.
     *
     * @param  App\Models\Cota  $cota
     * @return void
     */
    private function desvincularCursos(Cota $cota)
    {
        $this->authorize('isAdmin', User::class);
        foreach ($cota->cursos as $curso) {
            $curso->cotas()->detach($cota->id);
        }
    }

    /**
     * Retorna as informações de uma cota passada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function infoCota(Request $request)
    {
        $cota = Cota::find($request->cota_id);
        $cursos = [];
        foreach ($cota->cursos as $curso) {
            $curso_pivot = [
                'id' => $curso->id,
                'nome' => $curso->nome,
                'quantidade' => $curso->pivot->quantidade_vagas,
            ];
            array_push($cursos, $curso_pivot);
        }

        $cotaInfo = [
            'id' => $cota->id,
            'nome' => $cota->nome,
            'descricao' => $cota->descricao,
            'cod_cota' => $cota->cod_cota,
            'cod_novo' => $cota->cod_novo,
            'cod_siga' => $cota->cod_siga,
            'cursos' => $cursos,
        ];

        return response()->json($cotaInfo);
    }

    /**
     * Função que atualiza via modal uma cota.
     *
     * @param  \App\Http\Requests\CotaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function updateModal(CotaRequest $request)
    {
        $ultimoSisu = \App\Models\Sisu::orderBy('id', 'desc')->first();
        $this->authorize('isAdmin', \App\Models\User::class);
        $cota = Cota::find($request->cota);
        $request->validated();
        $validated = $this->validarOpcionalObrigatorio($request);
        if ($validated != null) {
            return $validated;
        }

        $cota->setAtributes($request);
        $cota->update();

        foreach ($request->cursos as $i => $curso_id) {
            if ($curso_id != null) {
                $curso = Curso::find($curso_id);
                $pivot = $curso->cotas()
                    ->where('cota_id', $cota->id)
                    ->where('sisu_id', $ultimoSisu->id)
                    ->first()
                    ->pivot;

                $pivot->update(['quantidade_vagas' => $request->quantidade[$i]]);
            }
        }

        return redirect(route('cotas.index'))->with(['success' => 'Cota atualizada com sucesso!']);
    }
}
