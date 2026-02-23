<?php

namespace App\Http\Controllers;

use App\Exports\SisuGestaoExport;
use App\Http\Requests\ChamadaRequest;
use App\Jobs\CadastroListaEsperaCandidato;
use App\Jobs\CadastroRegularCandidato;
use App\Models\Chamada;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\DataChamada;
use App\Models\Inscricao;
use App\Models\Listagem;
use App\Models\Sisu;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Facades\Excel;

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
        $this->authorize('isAdmin', User::class);
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
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $chamada = new Chamada();
        $chamada->setAtributes($request);

        $chamada->sisu_id = $request->sisu;

        if ($request->regular == "true") {
            $chamada->confirmacao = false;
            $chamada->regular = true;
        } else {
            $chamada->confirmacao = true;
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
    public function show($id)
    {
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($id);
        $datas = $chamada->datasChamada;
        $listagens = $chamada->listagem;

        return view('chamada.show', compact('chamada', 'datas', 'listagens'))->with(['tipos_data' => DataChamada::TIPO_ENUM, 'tipos_listagem' => Listagem::TIPO_ENUM, 'cursos' => Curso::all(), 'cotas' => Cota::all()]);;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('isAdmin', User::class);
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
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $chamada = Chamada::find($id);
        $chamada->setAtributes($request);

        if ($request->regular == "true") {
            $chamada->regular = true;
        } else {
            $chamada->confirmacao = true;
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
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($id);
        if ($chamada->inscricoes()->count() > 0) {
            /*foreach($chamada->inscricoes as $inscricao){
                $inscricao->delete();
                $inscricao->candidato->delete();
                $inscricao->candidato->user->delete();
            }*/
            return redirect()->back()->with(['error' => 'Não foi possível deletar esta chamada, há inscrições de candidatos nela.']);
        } elseif ($chamada->listagem()->count() > 0) {
            return redirect()->back()->with(['error' => 'Não foi possível deletar esta chamada, há listagens criadas para ela.']);
        }
        $sisu = Sisu::find($chamada->sisu_id);
        $chamada->delete();

        return redirect(route('sisus.show', ['sisu' => $sisu->id]))->with(['success' => 'Chamada deletada com sucesso!']);
    }

    public function importarCandidatos(Request $request, $sisu_id, $chamada_id)
    {
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($chamada_id);

        if ($chamada->regular) {
            $batch = Bus::batch([
                new CadastroRegularCandidato($chamada),
            ])->name('Importar Chamada Regular ' . $chamada->id)->dispatch();
            $chamada->job_batch_id = $batch->id;
        } else {
            if ($chamada->confirmacao) {
                $sisu = $chamada->sisu;
                if ($sisu->caminho_import_espera == null) {
                    return redirect()->back()->withErrors(['error_espera' => 'Arquivo de espera ausente, envie a lista de espera e tente novamente.'])->withInput($request->all());
                }

                //$this->gerarListagemConfirmacao($chamada);Utilizar useCase para criar a lista confirmação/convocação
                $chamada->confirmacao = false;
                $chamada->update();
                return redirect(route('chamadas.show', $chamada))->with(['success_listagem' =>  'Listagem criada com sucesso!']);
            } else {
                $batch = Bus::batch([
                    new CadastroListaEsperaCandidato($chamada),
                ])->name('Importar Chamada Lista Espera ' . $chamada->id)->dispatch();
                $chamada->job_batch_id = $batch->id;
            }
        }
        $chamada->update();
        return redirect(route('sisus.show', ['sisu' => $chamada->sisu->id]))->with(['success' => 'Cadastro feito!']);
    }

    public function candidatosChamada($sisu_id, $chamada_id)
    {
        $chamada = Chamada::find($chamada_id);
        $this->authorize('isAdminOrAnalista', User::class);
        $concluidos = collect();
        $concluidosPendentes = collect();
        $enviados = collect();
        $naoEnviados = collect();
        $invalidados = collect();

        $cursos = auth()->user()->analistaCursos()->orderBy('nome')->get();
        $cursos = auth()->user()->role !== User::ROLE_ENUM['analista'] ? Curso::orderBy('nome')->get() : $cursos;
        $userPolicy = new UserPolicy();

        $L2 = Cota::where('cod_cota', 'L2')->first();
        $L6 = Cota::where('cod_cota', 'L6')->first();
        $L9 = Cota::where('cod_cota', 'L9')->first();
        $LI_Q = Cota::where('cod_cota', 'LI_Q')->first();
        $L13 = Cota::where('cod_cota', 'L13')->first();
        $LB_Q = Cota::where('cod_cota', 'LB_Q')->first();

        $cotas = auth()->user()->analistaCotas()->pluck('cota_id')->toArray();
        $cotas = auth()->user()->role !== User::ROLE_ENUM['analista'] ? Cota::pluck('id') : $cotas;

        foreach ($cursos as $curso) {
            if ($userPolicy->isAdminOrAnalistaGeral(auth()->user())) {
                $candidatosConcluidos = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias']]])->whereIn('cota_id', $cotas)->get();
                $candidatosConcluidosPendencia = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias']]])->whereIn('cota_id', $cotas)->get();
                $candidatosNaoEnviado = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_pendentes']]])->whereIn('cota_id', $cotas)->get();
                $candidatosEnviado = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_enviados']]])->whereIn('cota_id', $cotas)->get();
                $candidatosInvalidados = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_invalidados']]])->whereIn('cota_id', $cotas)->get();
            } else if ($userPolicy->soEhAnalistaHeteroidentificacao(auth()->user())) {
                $retorno = $this->inscricoesHeteroidentificacao($chamada, $curso, [$L2->id, $L6->id, $LI_Q->id, $LB_Q->id], ['fotografia', 'heteroidentificacao', 'declaracao_cotista']);
                $candidatosConcluidos = $retorno['candidatosConcluidos'];
                $candidatosConcluidosPendencia = $retorno['candidatosConcluidosPendencia'];
                $candidatosNaoEnviado = $retorno['candidatosNaoEnviado'];
                $candidatosEnviado = $retorno['candidatosEnviado'];
                $candidatosInvalidados = $retorno['candidatosInvalidados'];
            } elseif ($userPolicy->soEhAnalistaMedico(auth()->user())) {
                $retorno = $this->inscricoesMedico($chamada, $curso, [$L9->id, $L13->id]);
                $candidatosConcluidos = $retorno['candidatosConcluidos'];
                $candidatosConcluidosPendencia = $retorno['candidatosConcluidosPendencia'];
                $candidatosNaoEnviado = $retorno['candidatosNaoEnviado'];
                $candidatosEnviado = $retorno['candidatosEnviado'];
                $candidatosInvalidados = $retorno['candidatosInvalidados'];
            } else if ($userPolicy->ehAnalistaHeteroidentificacaoEMedico(auth()->user())) {
                $inscricoes1 = $this->inscricoesHeteroidentificacao($chamada, $curso, [$L2->id, $L6->id, $LB_Q->id, $LI_Q->id], ['fotografia', 'heteroidentificacao', 'declaracao_cotista']);
                $candidatosConcluidos = $inscricoes1['candidatosConcluidos'];
                $candidatosConcluidosPendencia = $inscricoes1['candidatosConcluidosPendencia'];
                $candidatosNaoEnviado = $inscricoes1['candidatosNaoEnviado'];
                $candidatosEnviado = $inscricoes1['candidatosEnviado'];
                $candidatosInvalidados = $inscricoes1['candidatosInvalidados'];

                $inscricoes3 = $this->inscricoesMedico($chamada, $curso, [$L9->id, $L13->id]);
                $candidatosConcluidos = $candidatosConcluidos->concat($inscricoes3['candidatosConcluidos'])->unique();
                $candidatosConcluidosPendencia = $candidatosConcluidosPendencia->concat($inscricoes3['candidatosConcluidosPendencia'])->unique();
                $candidatosNaoEnviado = $candidatosNaoEnviado->concat($inscricoes3['candidatosNaoEnviado'])->unique();
                $candidatosEnviado = $candidatosEnviado->concat($inscricoes3['candidatosEnviado'])->unique();
                $candidatosInvalidados = $candidatosInvalidados->concat($inscricoes3['candidatosInvalidados'])->unique();
            } else {
                $candidatosConcluidos = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias']]])->get();
                $candidatosConcluidosPendencia = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias']]])->get();
                $candidatosNaoEnviado = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_pendentes']]])->get();
                $candidatosEnviado = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_enviados']]])->get();
                $candidatosInvalidados = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_invalidados']]])->get();
            }

            $concluidos->push(count($candidatosConcluidos));
            $concluidosPendentes->push(count($candidatosConcluidosPendencia));
            $enviados->push(count($candidatosEnviado));
            $naoEnviados->push(count($candidatosNaoEnviado));
            $invalidados->push(count($candidatosInvalidados));
        }

        return view('chamada.candidatos-chamada', compact('chamada', 'cursos', 'concluidos', 'concluidosPendentes', 'enviados', 'naoEnviados', 'invalidados'))
            ->with(['turnos' => Curso::TURNO_ENUM, 'graus' => Curso::GRAU_ENUM]);
    }

    private function inscricoesHeteroidentificacao($chamada, $curso, $cotas, $arquivos)
    {
        $retorno = [];
        $retorno['candidatosConcluidos'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->where(function ($qry) {
                $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                    ->orWhereNull('candidatos.etnia_e_cor');
            })
            ->whereIn('inscricaos.id', function ($qry) use ($arquivos) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', $arquivos)
                    ->whereIn('avaliacaos.avaliacao', [1])
                    ->groupBy('inscricaos.id')
                    ->havingRaw('COUNT(*) = ?', [count($arquivos)])
                    ->get();
            })
            ->get();

        $retorno['candidatosConcluidosPendencia'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->where(function ($qry) {
                $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                    ->orWhereNull('candidatos.etnia_e_cor');
            })->get();

        $retorno['candidatosNaoEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_pendentes']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->where(function ($qry) {
                $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                    ->orWhereNull('candidatos.etnia_e_cor');
            })->get();

        $retorno['candidatosEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_enviados']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->where(function ($qry) {
                $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                    ->orWhereNull('candidatos.etnia_e_cor');
            })
            ->whereNotIn('inscricaos.id', function ($qry) use ($arquivos) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', $arquivos)
                    ->whereIn('avaliacaos.avaliacao', [1, 2])
                    ->groupBy('inscricaos.id')
                    ->havingRaw('COUNT(*) = ?', [count($arquivos)])
                    ->get();
            })
            ->get();

        $retorno['candidatosInvalidados'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->where(function ($qry) {
                $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                    ->orWhereNull('candidatos.etnia_e_cor');
            })
            ->whereIn('inscricaos.id', function ($qry) use ($arquivos) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', $arquivos)
                    ->whereIn('avaliacaos.avaliacao', [1, 2])
                    ->whereIn('inscricaos.id', function ($sub) use ($arquivos) {
                        $sub->select('inscricaos.id')
                            ->from('inscricaos')
                            ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                            ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                            ->whereIn('arquivos.nome', $arquivos)
                            ->whereIn('avaliacaos.avaliacao', [2])
                            ->groupBy('inscricaos.id')
                            ->get();
                    })
                    ->groupBy('inscricaos.id')
                    ->havingRaw('COUNT(*) = ?', [count($arquivos)])
                    ->get();
            })
            ->get();
        return $retorno;
    }

    private function inscricoesMedico($chamada, $curso, $cotas)
    {
        $retorno = [];
        $retorno['candidatosConcluidos'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->whereIn('inscricaos.id', function ($qry) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                    ->whereIn('avaliacaos.avaliacao', [1])
                    ->groupBy('inscricaos.id')
                    ->havingRaw('COUNT(*) = ?', [2])
                    ->get();
            })
            ->get();

        $retorno['candidatosConcluidosPendencia'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->get();

        $retorno['candidatosNaoEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_pendentes']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->get();

        $retorno['candidatosEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_enviados']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->whereNotIn('inscricaos.id', function ($qry) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                    ->whereIn('avaliacaos.avaliacao', [1, 2])
                    ->groupBy('inscricaos.id')
                    ->havingRaw('COUNT(*) = ?', [2])
                    ->get();
            })
            ->get();

        $retorno['candidatosInvalidados'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
            ->join('users', 'users.id', '=', 'candidatos.user_id')
            ->whereIn('inscricaos.id', function ($qry) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                    ->where('avaliacaos.avaliacao', 2) // Apenas rejeições
                    ->groupBy('inscricaos.id')
                    ->get();
            })
            ->get();
        return $retorno;
    }

    public function candidatosCurso(Request $request, $sisu_id, $chamada_id, $curso_id)
    {
        $this->authorize('isAdminOrAnalista', User::class);
        $chamada = Chamada::find($chamada_id);
        $curso = Curso::find($curso_id);
        $sisu = Sisu::find($sisu_id);
        $turno = $curso->getTurno();
        $ordem = $request->ordem;
        $cotas = auth()->user()->analistaCotas->pluck('id');
        $cotas = auth()->user()->role !== User::ROLE_ENUM['analista'] ? Cota::pluck('id') : $cotas;


        $userPolicy = new UserPolicy();
        $concluidos = collect();
        $invalidados = collect();
        if ($userPolicy->isAdminOrAnalistaGeral(auth()->user())) {
            $query = Inscricao::select('inscricaos.*')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', $cotas)
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('users', 'users.id', '=', 'candidatos.user_id');
        } elseif ($userPolicy->soEhAnalistaHeteroidentificacao(auth()->user())) {
            $L2 = Cota::where('cod_cota', 'L2')->first();
            $L6 = Cota::where('cod_cota', 'L6')->first();
            $LB_Q = Cota::where('cod_cota', 'LB_Q')->first();
            $LI_Q = Cota::where('cod_cota', 'LI_Q')->first();
            $query = Inscricao::select('inscricaos.*')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('users', 'users.id', '=', 'candidatos.user_id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id, $LB_Q->id, $LI_Q->id])
                ->where(function ($qry) {
                    $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                        ->orWhereNull('candidatos.etnia_e_cor');
                });
            $concluidos = Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->where(function ($qry) {
                    $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                        ->orWhereNull('candidatos.etnia_e_cor');
                })
                ->whereIn('cota_id', [$L2->id, $L6->id, $LB_Q->id, $LI_Q->id])
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [3])
                ->get();
            $invalidados = Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id, $LB_Q->id, $LI_Q->id])
                ->where(function ($qry) {
                    $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                        ->orWhereNull('candidatos.etnia_e_cor');
                })
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function ($sub) {
                    $sub->select('inscricaos.id')
                        ->from('inscricaos')
                        ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                        ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                        ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                        ->whereIn('avaliacaos.avaliacao', [2])
                        ->groupBy('inscricaos.id')
                        ->get();
                })
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [3])
                ->get();
        } elseif ($userPolicy->soEhAnalistaMedico(auth()->user())) {
            $L9 = Cota::where('cod_cota', 'L9')->first();
            $L13 = Cota::where('cod_cota', 'L13')->first();
            $query = Inscricao::select('inscricaos.*')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('users', 'users.id', '=', 'candidatos.user_id');
            $concluidos = Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [2])
                ->get();
            $invalidados = Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function ($sub) {
                    $sub->select('inscricaos.id')
                        ->from('inscricaos')
                        ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                        ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                        ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                        ->whereIn('avaliacaos.avaliacao', [2])
                        ->groupBy('inscricaos.id')
                        ->get();
                })
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [2])
                ->get();
        } elseif ($userPolicy->ehAnalistaHeteroidentificacaoEMedico(auth()->user())) {
            $L2 = Cota::where('cod_cota', 'L2')->first();
            $L6 = Cota::where('cod_cota', 'L6')->first();
            $L9 = Cota::where('cod_cota', 'L9')->first();
            $LI_Q = Cota::where('cod_cota', 'LI_Q')->first();
            $L13 = Cota::where('cod_cota', 'L13')->first();
            $LB_Q = Cota::where('cod_cota', 'LB_Q ')->first();

            $query = Inscricao::select('inscricaos.*')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('users', 'users.id', '=', 'candidatos.user_id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id, $LI_Q->id, $LB_Q->id])
                ->where(function ($qry) {
                    $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                        ->orWhereNull('candidatos.etnia_e_cor');
                })
                ->get();
            $concluidos = Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->where(function ($qry) {
                    $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                        ->orWhereNull('candidatos.etnia_e_cor');
                })
                ->whereIn('cota_id', [$L2->id, $L6->id])
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [3])
                ->get();
            $invalidados = Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id])
                ->where(function ($qry) {
                    $qry->whereIn('candidatos.etnia_e_cor', [2, 3])
                        ->orWhereNull('candidatos.etnia_e_cor');
                })
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function ($sub) {
                    $sub->select('inscricaos.id')
                        ->from('inscricaos')
                        ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                        ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                        ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                        ->whereIn('avaliacaos.avaliacao', [2])
                        ->groupBy('inscricaos.id')
                        ->get();
                })
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [3])
                ->get();

            $query = $query->concat(Inscricao::select('inscricaos.*')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('users', 'users.id', '=', 'candidatos.user_id')
                ->get());
            $concluidos = $concluidos->concat(Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [2])
                ->get());
            $invalidados = $invalidados->concat(Inscricao::select('inscricaos.id')
                ->join('candidatos', 'inscricaos.candidato_id', '=', 'candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function ($sub) {
                    $sub->select('inscricaos.id')
                        ->from('inscricaos')
                        ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                        ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                        ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                        ->whereIn('avaliacaos.avaliacao', [2])
                        ->groupBy('inscricaos.id')
                        ->get();
                })
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [2])
                ->get());
            $query = $query->unique();
            switch ($ordem) {
                case 'name':
                    $candidatos = $query->sortBy('candidato.user.name');
                    break;
                case 'cota':
                    $candidatos = $query->sortBy('cota_id');
                    break;
                case 'status':
                    $candidatos = $query->sortBy('status');
                    break;
                default:
                    $candidatos = $query->sortBy('candidato.user.name');
                    break;
            }
            return view('chamada.candidatos-curso', compact('chamada', 'curso', 'candidatos', 'turno', 'sisu', 'ordem', 'concluidos', 'invalidados'));
        }

        switch ($ordem) {
            case 'name':
                $candidatos = $query->orderBy('users.name')->get();
                break;
            case 'cota':
                $candidatos = $query->orderBy('inscricaos.cota_id')->get();
                break;
            case 'status':
                $candidatos = $query->orderBy('inscricaos.status')->get();
                break;
            default:
                $candidatos = $query->orderBy('name')->get();
                break;
        }


        return view('chamada.candidatos-curso', compact('chamada', 'curso', 'candidatos', 'turno', 'sisu', 'ordem', 'concluidos', 'invalidados'));
    }

    public function aprovarCandidatosChamada($sisu_id, $chamada_id)
    {
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($chamada_id);
        $quantidade = $chamada->inscricoes()->get()->count();
        $candidatos = $chamada->inscricoes()->inRandomOrder()->limit((int)$quantidade / 2)->get();
        foreach ($candidatos as $candidato) {
            $candidato->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
            $candidato->cd_efetivado = Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'];

            $cotaRemanejamento = $candidato->cotaRemanejada;
            if ($cotaRemanejamento != null) {
                $cota = $cotaRemanejamento;
            } else {
                $cota = $candidato->cota;
            }

            $curso = $candidato->curso;

            $cota_curso = $curso->cotas()->where('cota_id', $cota->id)->where('sisu_id', $chamada->sisu->id)->first()->pivot;
            $cota_curso->vagas_ocupadas += 1;

            $candidato->update();
            $cota_curso->update();
        }
        return redirect()->back()->with(['success' => 'Candidatos efetivados com sucesso.']);
    }

    public function exportarCSVSisuGestao(Request $request)
    {
        $chamada = Chamada::find($request->chamada);

        $validados = Inscricao::where([['chamada_id', $chamada->id], ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]])
            ->get()->map(function ($candidato) {
                return [
                    $candidato->co_inscricao_enem,
                    'M',
                ];
            })->collect();

        $candidatos = Inscricao::where('chamada_id', $chamada->id)
            ->whereIn('status', [Inscricao::STATUS_ENUM['documentos_pendentes'], Inscricao::STATUS_ENUM['documentos_invalidados']])
            ->get()->map(function ($candidato) {
                return [
                    $candidato->co_inscricao_enem,
                    $this->situacaoMatricula($candidato->status),
                ];
            })->collect();

        return Excel::download(
            new SisuGestaoExport($validados->merge($candidatos)),
            'sisu_gestao_export_' . $chamada->nome . '.csv',
            \Maatwebsite\Excel\Excel::CSV,
            ['Content-Type' => 'text/csv']
        );
    }

    private function situacaoMatricula($status)
    {
        $matriculas = [
            Inscricao::STATUS_ENUM['documentos_pendentes'] => 'N',
            Inscricao::STATUS_ENUM['documentos_invalidados'] => 'R',
        ];
        return $matriculas[$status];
    }
}
