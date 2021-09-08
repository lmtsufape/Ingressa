<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\Remanejamento;
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
        $validated = $this->validarOpcionalObrigatorioRemanejamento($request);
        if ($validated != null) {
            return $validated;
        }
        $cota = Cota::find($id);

        $this->vincularOrdem($request, $cota);
        
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
        switch ($request->modo) {
            case 'create':
                foreach ($request->cotas as $i => $valor) {
                    if ($valor != null) {
                        $prox_cota = Cota::find($valor);
                        if ($prox_cota != null) {
                            $this->criarRemanejamento($request->ordem[$i], $cota, $prox_cota);
                        }
                    }
                }
                break;
            case 'edit':
                // Deletando desmarcados
                $remanejamentos_excluidos = collect();
                foreach ($cota->remanejamentos as $remanejamento) {
                    if (!in_array($remanejamento->id_prox_cota, $request->cotas)) {
                        $remanejamentos_excluidos->push($remanejamento);
                    }
                }
                foreach ($remanejamentos_excluidos as $remanejamento) {
                    $remanejamento->delete();
                }

                // Atualizando marcados e criando novos marcados
                foreach ($request->cotas as $i => $valor) {
                    if ($cota->remanejamentos->contains('id_prox_cota', $valor)) {
                        $prox_cota = Cota::find($valor);
                        $remanejamento = $cota->remanejamentos()->where('id_prox_cota', $valor)->first();
                        $remanejamento->setAtributes($request->ordem[$i], $cota, $prox_cota);
                        $remanejamento->update();
                    } else {
                        $prox_cota = Cota::find($valor);
                        if ($prox_cota != null) {
                            $this->criarRemanejamento($request->ordem[$i], $cota, $prox_cota);
                        }
                    }
                }
                break;
        }
    }

    /**
     *  Cria um remanejamento com os valores passados.
     * @param  int $ordem
     * @param  App\Models\Cota  $cota
     * @param  App\Models\Cota  $prox_cota
     * @return void
     */
    private function criarRemanejamento($ordem, Cota $cota, Cota $prox_cota)
    {
        $remanejamento = new Remanejamento();
        $remanejamento->setAtributes($ordem, $cota, $prox_cota);
        $remanejamento->save();
    }

    /**
     * Checa se um checkbox foi marcado mais faltou o preenchimento do campo da ordem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function validarOpcionalObrigatorioRemanejamento(Request $request)
    {
        foreach ($request->cotas as $i => $valor) {
            if ($valor != null && $request->ordem[$i] == null) {
                return redirect()->back()->withErrors(['ordem.'.$i => 'O campo de ordem é obrigatório caso a cota esteja marcada.'])->withInput($request->all());
            }
        }
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
    private function vincularCursos(CotaRequest $request, Cota $cota)
    {
        foreach ($request->cursos as $i => $curso_id) {
            if ($curso_id != null) {
                $curso = Curso::find($curso_id);
                $curso->cotas()->attach($cota->id, ['percentual_cota' => $request->percentual[$i]]);
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
        foreach ($cota->cursos as $curso) {
            $curso->cotas()->detach($cota->id);
        }
    }
}
