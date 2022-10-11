<?php

namespace App\Http\Controllers;

use Excel;
use App\Exports\AprovadosExport;
use App\Exports\SisuGestaoExport;
use App\Http\Requests\ListagemRequest;
use App\Models\Listagem;
use Illuminate\Http\Request;
use App\Models\Inscricao;
use App\Models\Cota;
use App\Models\Curso;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Chamada;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Bus;
use App\Jobs\EnviarEmailsPublicacaoListagem;
use Illuminate\Support\Facades\Log;

class ListagemController extends Controller
{
    private $periodos = [];
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
    public function store(ListagemRequest $request)
    {
        $this->authorize('isAdmin', User::class);
        $request->validated();
        $listagem = new Listagem();
        $listagem->setAtributes($request);
        $listagem->caminho_listagem = 'caminho';
        $listagem->save();

        switch ($request->tipo) {
            case Listagem::TIPO_ENUM['convocacao']:
                $listagem->caminho_listagem = $this->gerarListagemConvocacao($request, $listagem);
                break;
            case Listagem::TIPO_ENUM['pendencia']:
                $listagem->caminho_listagem = $this->gerarListagemPendencia($request, $listagem);
                break;
            case Listagem::TIPO_ENUM['resultado']:
                $listagem->caminho_listagem = $this->gerarListagemResultado($request, $listagem);
                break;
            case Listagem::TIPO_ENUM['final']:
                $listagem->caminho_listagem = $this->gerarListagemFinal($request, $listagem);
                break;
        }
        $listagem->update();

        return redirect()->back()->with(['success_listagem' => 'Listagem criada com sucesso']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Listagem  $listagem
     * @return \Illuminate\Http\Response
     */
    public function show(Listagem $listagem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Listagem  $listagem
     * @return \Illuminate\Http\Response
     */
    public function edit(Listagem $listagem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Listagem  $listagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Listagem $listagem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Listagem  $listagem
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin', User::class);
        $listagem = Listagem::find($id);

        if (Storage::disk()->exists('public/' .$listagem->caminho_listagem)) {
            Storage::delete('public/'.$listagem->caminho_listagem);
        }

        $listagem->delete();

        return redirect()->back()->with(['success_listagem' => 'Listagem deletada com sucesso.']);
    }

    /**
     * Gera o arquivo pdf da listagem de convocacao e retorna o caminho do arquivo.
     *
     * @param  \App\Http\Requests\ListagemRequest  $request
     * @return string $caminho_do_arquivo
     */
    private function gerarListagemConvocacao(ListagemRequest $request, Listagem $listagem)
    {
        $chamada = Chamada::find($request->chamada);
        $cursos = Curso::whereIn('id', $request->cursos)->orderBy('nome')->get();
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('id')->get();
        $inscricoes = collect();
        $ordenacao = $this->get_ordenacao($request);
        $ordem = $this->get_ordem($request);

        foreach ($cursos as $i => $curso) {
            $inscricoes_curso = collect();
            if($curso->turno == Curso::TURNO_ENUM['matutino']){
                $turno = 'Matutino';
            }elseif($curso->turno == Curso::TURNO_ENUM['vespertino']){
                $turno = 'Vespertino';
            }elseif($curso->turno == Curso::TURNO_ENUM['noturno']){
                $turno = 'Noturno';
            }elseif($curso->turno == Curso::TURNO_ENUM['integral']){
                $turno = 'Integral';
            }
            $ampla = collect();
            foreach ($cotas as $j => $cota) {
                //Juntar todos aqueles que são da ampla concorrencia independente do bonus de 10%
                if($cota->getCodCota() == Cota::COD_COTA_ENUM['A0']){
                    $ampla2 = Inscricao::select('inscricaos.*')
                        ->where([['curso_id', $curso->id], ['cota_id', $cota->id], ['chamada_id', $chamada->id]])
                        ->whereIn(
                            'no_modalidade_concorrencia',
                            [
                                'Ampla concorrência',
                                'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
                                'AMPLA CONCORRÊNCIA'
                            ]
                        )
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla2 = $ampla2->map->only(['id']);
                    $ampla = $ampla->concat($ampla2);
                }else if($cota->getCodCota() == Cota::COD_COTA_ENUM['B4342']){
                    //ignorar a de 10% visto que entra na mesma tabela que A0
                }else{
                    $inscritosCota = Inscricao::select('inscricaos.*')->
                    where([['curso_id', $curso->id], ['cota_id', $cota->id], ['chamada_id', $chamada->id]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    if($inscritosCota->count() > 0 ){
                        $inscritosCota = $inscritosCota->map->only(['id']);
                        $inscricoes_curso->push($inscritosCota);
                    }
                }
            }
            if($ampla->count() > 0){
                $inscricoes_curso->prepend($ampla);
            }
            if ($inscricoes_curso->count() > 0) {
                $inscricoes->push($inscricoes_curso);
            }
        }
        $pdf = PDF::loadView('listagem.inscricoes', ['collect_inscricoes' => $inscricoes, 'chamada' => $chamada]);

        return $this->salvarListagem($listagem, $pdf->stream());
    }

    /**
     * Salva o arquivo de listagem em seu diretorio.
     *
     * @param  \App\Models\Listagem  $listagem
     * @param  string $arquivo
     * @return string $caminho_do_arquivo
     */
    private function salvarListagem(Listagem $listagem, $arquivo)
    {
        $path = 'listagem/' . $listagem->id . '/';
        $nome = 'listagem.pdf';
        Storage::put('public/' . $path . $nome, $arquivo);
        return $path . $nome;
    }

    /**
     * Gera o arquivo pdf da listagem de resultado e retorna o caminho do arquivo.
     *
     * @param  \App\Http\Requests\ListagemRequest  $request
     * @return string $caminho_do_arquivo
     */
    private function gerarListagemResultado(ListagemRequest $request, Listagem $listagem)
    {
        //
        $chamada = Chamada::find($request->chamada);
        $cursos = Curso::whereIn('id', $request->cursos)->orderBy('nome')->get();
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('id')->get();
        $inscricoes = collect();
        $ordenacao = $this->get_ordenacao($request);
        $ordem = $this->get_ordem($request);

        foreach ($cursos as $curso) {
            $inscricoes_curso = collect();
            if($curso->turno == Curso::TURNO_ENUM['matutino']){
                $turno = 'Matutino';
            }elseif($curso->turno == Curso::TURNO_ENUM['vespertino']){
                $turno = 'Vespertino';
            }elseif($curso->turno == Curso::TURNO_ENUM['noturno']){
                $turno = 'Noturno';
            }elseif($curso->turno == Curso::TURNO_ENUM['integral']){
                $turno = 'Integral';
            }
            if($cotas->where('cod_cota', 'A0')){
                $modalidadeCotaArray = [
                    'Ampla concorrência',
                    'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
                    'AMPLA CONCORRÊNCIA'
                ];
            }else{
                $modalidadeCotaArray = [];
            }

            $modalidadeCotaArray = array_merge($modalidadeCotaArray, $cotas->pluck('descricao')->toArray());
            $inscricoes_curso = Inscricao::select('inscricaos.*')->
                where([['curso_id', $curso->id], ['chamada_id', $chamada->id]])
                ->whereIn(
                    'no_modalidade_concorrencia',
                    $modalidadeCotaArray
                )
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('users','users.id','=','candidatos.user_id')
                ->orderBy($ordenacao, $ordem)
                ->get();

            if ($inscricoes_curso->count() > 0) {
                $inscricoes_curso = $inscricoes_curso->map->only(['id']);
                $inscricoes->push($inscricoes_curso);
            }
        }
        $pdf = PDF::loadView('listagem.resultado', ['collect_inscricoes' => $inscricoes, 'chamada' => $chamada])->setPaper('a4', 'landscape');

        return $this->salvarListagem($listagem, $pdf->stream());
    }

    private function gerarListagemFinal(ListagemRequest $request, Listagem $listagem)
    {
        $chamada = Chamada::find($request->chamada);
        $inscricoes = $this->getInscricoesIngressantesReservas($request);

        $pdf = PDF::loadView('listagem.final', ['candidatosIngressantesCursos' => $inscricoes['ingressantes'], 'candidatosReservaCursos' => $inscricoes['reservas'], 'chamada' => $chamada]);

        return $this->salvarListagem($listagem, $pdf->stream());
    }

    public function getInscricoesIngressantesReservas($request)
    {
        $chamada = Chamada::find($request->chamada);
        $sisu = $chamada->sisu;
        $cursos = Curso::all();
        $cotas = Cota::all();
        $candidatosIngressantesCursos = collect();
        $candidatosReservaCursos = collect();
        $A0 = Cota::where('cod_cota', 'A0')->first();
        $l9 = Cota::where('cod_cota', 'L9')->first();
        $l13 = Cota::where('cod_cota', 'L13')->first();

        foreach($cursos as $curso){
            $vagas_restantes = array();
            $cpfs = collect();
            //$nomes = collect();

            $candidatosIngressantesCurso = collect();

            //APLICAR REGRA DE MAIORES NOTAS OCUPAREM A A0
            $candidatosCurso = Inscricao::where(
                [
                    ['sisu_id', $sisu->id],
                    ['curso_id', $curso->id],
                    ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]
                ]
            )->get();

            $candidatosCurso = $candidatosCurso->sortByDesc(function($candidato){
                return $candidato['nu_nota_candidato'];
            });


            $cota_curso_quantidade = $curso->cotas()->where('cota_id', $A0->id)->first()->pivot->quantidade_vagas;
            $curso80 = false;

            if($cota_curso_quantidade == 40) {
                $cota_curso_quantidade = 20;
                $curso80 = true;
            }

            foreach ($candidatosCurso as $candidato) {
                if ($cota_curso_quantidade > 0) {
                    if (!$cpfs->contains($candidato->candidato->nu_cpf_inscrito)) {
                        $candidato->cota_vaga_ocupada_id = $A0->id;
                        $candidatosIngressantesCurso->push($candidato);
                        $cota_curso_quantidade -= 1;
                        $cpfs->push($candidato->candidato->nu_cpf_inscrito);
                        //$nomes->push($candidato->candidato->no_inscrito.' '.$candidato->cotaRemanejada->cod_cota);
                    }
                }
            }

            foreach($cotas as $cota){
                if ($cota->cod_cota == $l9->cod_cota || $cota->cod_cota == $l13->cod_cota) {
                    $candidatosCotaCurso = Inscricao::where(
                        [
                            ['sisu_id', $sisu->id],
                            ['curso_id', $curso->id],
                            ['cota_id', $cota->id],
                            ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]
                        ]
                    )->get();

                    $candidatosCotaCurso = $candidatosCotaCurso->sortByDesc(function ($candidato) {
                        return $candidato['nu_nota_candidato'];
                    });
    
                    $cota_curso_quantidade = $curso->cotas()->where('cota_id', $cota->id)->first()->pivot->quantidade_vagas;
    
                    foreach ($candidatosCotaCurso as $candidato) {
                        if ($cota_curso_quantidade > 0) {
                            if (!$cpfs->contains($candidato->candidato->nu_cpf_inscrito)) {
                                $candidato->cota_vaga_ocupada_id = $cota->id;
                                $candidatosIngressantesCurso->push($candidato);
                                $cota_curso_quantidade -= 1;
                                $cpfs->push($candidato->candidato->nu_cpf_inscrito);
                            }
                        }
                    }
    
                    if ($cota_curso_quantidade > 0) {
                        foreach ($cota->remanejamentos as $remanejamento) {
                            $cotaRemanejamento = $remanejamento->proximaCota;
                            $candidatosCotaCursoRemanejamento = Inscricao::where(
                                [
                                        ['sisu_id', $sisu->id],
                                        ['curso_id', $curso->id],
                                        ['cota_id', $cotaRemanejamento->id],
                                        ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]
                                    ]
                            )->get();
        
                            $candidatosCotaCursoRemanejamento = $candidatosCotaCursoRemanejamento->sortByDesc(function ($candidato) {
                                return $candidato['nu_nota_candidato'];
                            });
        
                            $continua = false;
        
                            foreach ($candidatosCotaCursoRemanejamento as $candidato) {
                                if ($cota_curso_quantidade > 0) {
                                    if (!$cpfs->contains($candidato->candidato->nu_cpf_inscrito)) {
                                        $candidato->cota_vaga_ocupada_id = $cota->id;
                                        $candidatosIngressantesCurso->push($candidato);
                                        $cota_curso_quantidade -= 1;
                                        $cpfs->push($candidato->candidato->nu_cpf_inscrito);
                                    }
                                } else {
                                    $continua = true;
                                    break;
                                }
                            }
                            if ($continua) {
                                break;
                            }
                        }
                    }
                }
            }
            $vagas_restantes = array_fill(0, 8, 0);
            if ($curso80) {
                $cota_curso_quantidade = 20;
                foreach ($candidatosCurso as $candidato) {
                    if ($cota_curso_quantidade > 0) {
                        if (!$cpfs->contains($candidato->candidato->nu_cpf_inscrito)) {
                            $candidato->cota_vaga_ocupada_id = $A0->id;
                            $candidatosIngressantesCurso->push($candidato);
                            $cota_curso_quantidade -= 1;
                            $cpfs->push($candidato->candidato->nu_cpf_inscrito);
                            //$nomes->push($candidato->candidato->no_inscrito.' '.$candidato->cotaRemanejada->cod_cota);
                        }
                    }
                }
            }

            //dd($nomes);

            foreach($cotas as $i => $cota){
                if ($cota->cod_cota != $A0->cod_cota && $cota->cod_cota != $l9->cod_cota && $cota->cod_cota != $l13->cod_cota) {
                    $candidatosCotaCurso = Inscricao::where(
                        [
                            ['sisu_id', $sisu->id],
                            ['curso_id', $curso->id],
                            ['cota_id', $cota->id],
                            ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]
                        ]
                    )->get();

                    $candidatosCotaCurso = $candidatosCotaCurso->sortByDesc(function ($candidato) {
                        return $candidato['nu_nota_candidato'];
                    });

                    $cota_curso_quantidade = $curso->cotas()->where('cota_id', $cota->id)->first()->pivot->quantidade_vagas;

                    foreach ($candidatosCotaCurso as $candidato) {
                        if ($cota_curso_quantidade > 0) {
                            if (!$cpfs->contains($candidato->candidato->nu_cpf_inscrito)) {
                                $candidato->cota_vaga_ocupada_id = $cota->id;
                                $candidatosIngressantesCurso->push($candidato);
                                $cota_curso_quantidade -= 1;
                                $cpfs->push($candidato->candidato->nu_cpf_inscrito);
                            }
                        }
                    }

                    $vagas_restantes[$i] = $cota_curso_quantidade;
                }
            }

            //remanejamento depois
            foreach($cotas as $i => $cota){
                if ($cota->cod_cota != $A0->cod_cota && $cota->cod_cota != $l9->cod_cota && $cota->cod_cota != $l13->cod_cota) {
                    $cota_curso_quantidade = $vagas_restantes[$i];
                    if ($cota_curso_quantidade > 0) {
                        foreach ($cota->remanejamentos as $key => $remanejamento) {
                            $cotaRemanejamento = $remanejamento->proximaCota;
                            $candidatosCotaCursoRemanejamento = Inscricao::where(
                                [
                                        ['sisu_id', $sisu->id],
                                        ['curso_id', $curso->id],
                                        ['cota_id', $cotaRemanejamento->id],
                                        ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]
                                    ]
                            )->get();

                            $candidatosCotaCursoRemanejamento = $candidatosCotaCursoRemanejamento->sortByDesc(function ($candidato) {
                                return $candidato['nu_nota_candidato'];
                            });

                            $continua = false;

                            foreach ($candidatosCotaCursoRemanejamento as $candidato) {
                                if ($cota_curso_quantidade > 0) {
                                    if (!$cpfs->contains($candidato->candidato->nu_cpf_inscrito)) {
                                        $candidato->cota_vaga_ocupada_id = $cota->id;
                                        $candidatosIngressantesCurso->push($candidato);
                                        $cota_curso_quantidade -= 1;
                                        $cpfs->push($candidato->candidato->nu_cpf_inscrito);
                                        Log::info([$candidato->id, $candidato->cota->cod_cota,  $candidato->cotaRemanejada->cod_cota, $cotaRemanejamento->cod_cota]);
                                    }
                                } else {
                                    $continua = true;
                                    break;
                                }
                            }
                            if ($continua) {
                                break;
                            }
                        }
                    }
                }
            }
            if($candidatosIngressantesCurso->count() > 40){
                $primeiroSemestre = collect();
                $segundoSemestre = collect();

                $cotasL9L13 = Cota::whereIn('cod_cota', ['L9', 'L13'])->get();
                $retorno = $this->divirPorSemestre($cotasL9L13, $candidatosIngressantesCurso, $primeiroSemestre, $segundoSemestre, true);
                $primeiroSemestre = $retorno[0];
                $segundoSemestre = $retorno[1];

                $cotasL10L14 = Cota::whereIn('cod_cota', ['L10', 'L14'])->get();
                $retorno = $this->divirPorSemestre($cotasL10L14, $candidatosIngressantesCurso, $primeiroSemestre, $segundoSemestre, true);
                $primeiroSemestre = $retorno[0];
                $segundoSemestre = $retorno[1];

                $cotasNaoDeficientes = Cota::whereIn('cod_cota', ['A0', 'L1', 'L2', 'L5', 'L6'])->get();
                $retorno = $this->divirPorSemestre($cotasNaoDeficientes, $candidatosIngressantesCurso, $primeiroSemestre, $segundoSemestre, false);
                $primeiroSemestre = $retorno[0];
                $segundoSemestre = $retorno[1];

                $primeiroSemestre = $primeiroSemestre->sortBy(function($candidato){
                    return $candidato['cota_vaga_ocupada_id'];
                });

                $segundoSemestre = $segundoSemestre->sortBy(function($candidato){
                    return $candidato['cota_vaga_ocupada_id'];
                });

                $primeiroSemestre1 = collect();
                $segundoSemestre1 = collect();

                if($request->ordenacao == "nome"){
                    
                    $primeiroSemestre = $primeiroSemestre->groupBy('cota_vaga_ocupada_id');
                    foreach($primeiroSemestre as $candidatos){
                        $candidatos = $candidatos->sortBy(function($candidato){
                            return $candidato->candidato->no_inscrito;
                        });
                        $primeiroSemestre1 = $primeiroSemestre1->concat($candidatos);
                    }
                    $segundoSemestre = $segundoSemestre->groupBy('cota_vaga_ocupada_id');
                    foreach($segundoSemestre as $candidatos){
                        $candidatos = $candidatos->sortBy(function($candidato){
                            return $candidato->candidato->no_inscrito;
                        });
                        $segundoSemestre1 = $segundoSemestre1->concat($candidatos);
                    }
                }else{
                    $primeiroSemestre = $primeiroSemestre->groupBy('cota_vaga_ocupada_id');
                    foreach($primeiroSemestre as $candidatos){
                        $candidatos = $candidatos->sortByDesc(function($candidato){
                            return $candidato['nu_nota_candidato'];
                        });
                        $primeiroSemestre1 = $primeiroSemestre1->concat($candidatos);
                    }
                    $segundoSemestre = $segundoSemestre->groupBy('cota_vaga_ocupada_id');
                    foreach($segundoSemestre as $candidatos){
                        $candidatos = $candidatos->sortByDesc(function($candidato){
                            return $candidato['nu_nota_candidato'];
                        });
                        $segundoSemestre1 = $segundoSemestre1->concat($candidatos);
                    }
                }
                $primeiroSemestre1 = $primeiroSemestre1->map->only(['id', 'cota_vaga_ocupada_id']);
                $segundoSemestre1 = $segundoSemestre1->map->only(['id', 'cota_vaga_ocupada_id']);

                $candidatosIngressantesCursos->push($primeiroSemestre1);
                $candidatosIngressantesCursos->push($segundoSemestre1);
            }

            $candidatosIngressantesCurso1 = collect();
            $candidatosReservaCurso1 = collect();

            $candidatosIngressantesCurso = $candidatosIngressantesCurso->sortBy(function($candidato){
                return $candidato->cota_vaga_ocupada_id;
            });
            
            if($request->ordenacao == "nome"){
                $candidatosIngressantesCurso = $candidatosIngressantesCurso->groupBy('cota_vaga_ocupada_id');
                foreach($candidatosIngressantesCurso as $candidatos){
                    $candidatos = $candidatos->sortBy(function($candidato){
                        return $candidato->candidato->no_inscrito;
                    });
                    $candidatosIngressantesCurso1 = $candidatosIngressantesCurso1->concat($candidatos);
                }
            }else{
                $candidatosIngressantesCurso = $candidatosIngressantesCurso->groupBy('cota_vaga_ocupada_id');
                foreach($candidatosIngressantesCurso as $candidatos){
                    $candidatos = $candidatos->sortByDesc(function($candidato){
                        return $candidato['nu_nota_candidato'];
                    });
                    $candidatosIngressantesCurso1 = $candidatosIngressantesCurso1->concat($candidatos);
                }
            }

            $candidatosCurso = Inscricao::where([['sisu_id', $sisu->id], ['curso_id', $curso->id],
            ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]])->get();

            $candidatosReservaCurso = $candidatosCurso->diff($candidatosIngressantesCurso1);
            if($request->ordenacao == "nome"){
                $candidatosReservaCurso = $candidatosReservaCurso->groupBy('cota_vaga_ocupada_id');
                foreach($candidatosReservaCurso as $candidatos){
                    $candidatos = $candidatos->sortBy(function($candidato){
                        return $candidato->candidato->no_inscrito;
                    });
                    $candidatosReservaCurso1 = $candidatosReservaCurso1->concat($candidatos);
                }
            }else{
                $candidatosReservaCurso = $candidatosReservaCurso->groupBy('cota_vaga_ocupada_id');
                foreach($candidatosReservaCurso as $candidatos){
                    $candidatos = $candidatos->sortByDesc(function($candidato){
                        return $candidato['nu_nota_candidato'];
                    });
                    $candidatosReservaCurso1 = $candidatosReservaCurso1->concat($candidatos);
                }
            }
            $candidatosReservaCurso1 = $candidatosReservaCurso1->map->only(['id', 'cota_vaga_ocupada_id']);
            $candidatosIngressantesCurso1 = $candidatosIngressantesCurso1->map->only(['id', 'cota_vaga_ocupada_id']);

            $candidatosIngressantesCursos->push($candidatosIngressantesCurso1);
            if($candidatosReservaCurso1->first() != null){
                $candidatosReservaCursos->push($candidatosReservaCurso1);
            }
        }
        return ['ingressantes' => $candidatosIngressantesCursos, 'reservas' => $candidatosReservaCursos];
    }

    public function exportarCSV(Request $request)
    {
        $this->periodos = [
            '118468' => 0,
            '118466' => 0,
            '118470' => 0,
        ];

        $retorno = $this->getInscricoesIngressantesReservas($request)['ingressantes']
            ->filter(function ($value, $key) {
                return $value->count() <= 40;
            })
            ->map(function ($value, $key) {
                return $value->map(function ($value, $key) {
                    $value = Inscricao::find($value['id']);
                    return [
                        $value->candidato->nu_cpf_inscrito,
                        $value->nu_rg,
                        $this->removeAcentos($value->candidato->no_inscrito),
                        $this->getCodProgramaForm($value->curso),
                        $this->getPeriodo($value->curso),
                        $value->sisu->edicao,
                        $this->getTurno($value->curso),
                        2, //presencial
                        15, //sisu
                        $this->removeAcentos($value->no_mae),
                        $this->removeAcentos($value->candidato->pai),
                        $value->tp_sexo,
                        $this->getNacionalidade($value->candidato->pais_natural),
                        date('d/m/Y', strtotime($value->candidato->dt_nascimento)),
                        $value->candidato->estado_civil,
                        $this->removeAcentos($value->candidato->cidade_natal),
                        $value->nu_cep,
                        $this->getNumeroEndereco($value->nu_endereco),
                        $this->removeAcentos($value->ds_complemento),
                        date('d/m/Y', strtotime($value->candidato->data_expedicao)),
                        $value->candidato->orgao_expedidor,
                        $value->candidato->uf_rg,
                        'BRA',
                        $value->candidato->user->email,
                        //passaporte
                        $value->nu_nota_candidato,
                        //INSCRICAOVEST
                        //NOTAVEST
                        //CLASSVEST
                        $value->candidato->ano_conclusao,
                        $this->getCotaFinal($value->cota, $value->cotaRemanejada),
                        154575, //POLO DE RECIFE??
                        $value->candidato->cor_raca,
                        $value->candidato->titulo,
                        $value->candidato->zona_eleitoral,
                        $value->candidato->secao_eleitoral,
                        $value->nu_fone1,
                        $value->nu_fone2,
                        $this->removeAcentos($value->candidato->escola_ens_med),
                        //escolaridade mae
                        //escolaridade pai
                        $value->candidato->necessidades,
                    ];
                });
            })->collect();
        return Excel::download(
            new AprovadosExport($retorno),
            'ingressantes.csv',
            \Maatwebsite\Excel\Excel::CSV,
            ['Content-Type' => 'text/csv']
        );
    }

    public function exportarIngressantesEspera(Request $request)
    {
        $chamada = Chamada::find($request->chamada);

        $retorno = $this->getInscricoesIngressantesReservas($request)['ingressantes']
            ->filter(function ($value, $key) {
                return $value->count() <= 40;
            })
            ->map(function ($value, $key) {
                return $value->map(function ($value, $key) {
                    $value = Inscricao::find($value['id']);
                    if(!$value->chamada->regular){
                        return [
                            $value->co_inscricao_enem,
                            'M',
                        ];
                    }
                });
            })->collect();

        $ingressantes = collect();
        
        foreach($retorno as $curso){
            foreach($curso as $ingressante){
                if($ingressante != null){
                    $ingressantes->push($ingressante);
                }
            }
        }
        
        $candidatos = Inscricao::where('sisu_id', $chamada->sisu->id)
            ->whereIn('status', [Inscricao::STATUS_ENUM['documentos_pendentes'], Inscricao::STATUS_ENUM['documentos_invalidados']])
            ->get()->map(function ($candidato) {
                if(!$candidato->chamada->regular){
                    return [
                        $candidato->co_inscricao_enem,
                        $this->situacaoMatricula($candidato->status),
                    ];
                }
            })->collect();
        
        $candidatos = $candidatos->filter();
        return Excel::download(
            new SisuGestaoExport($ingressantes->merge($candidatos)),
            'ingressantesEspera.csv',
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

    private function getCotaFinal(Cota $cota, Cota $cotaRemanejada = null)
    {
        $codigos = [
            'B4342' => 0,
            'A0' => 0,
            'L2' => 3,
            'L1' => 4,
            'L6' => 5,
            'L5' => 6,
            'L10' => 9,
            'L9' => 10,
            'L14' => 11,
            'L13' => 12
        ];
        if($cotaRemanejada == null) return $codigos[$cota->cod_cota];
        return ($codigos[$cota->cod_cota]);
    }

    private function removeAcentos($palavra)
    {
        return strtr(utf8_decode($palavra), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ\''), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY ');
    }

    private function getNumeroEndereco($numero)
    {
        if ($numero == null | is_numeric($numero) && strlen(strval($numero)) <= 5) return $numero;
        return null;
    }

    private function getPeriodo(Curso $curso)
    {
        if($curso->semestre != null) {
            return $curso->semestre;
        }
        return $this->periodos[$curso->cod_curso]++ < 40 ? 1 : 2;

    }

    private function getNacionalidade($nacionalidade)
    {
        $nacionalidades = [
            'BRASIL' => 'BRA',
            null => '',
        ];
        if(str_contains(strtoupper($nacionalidade), "BRA")){
            $nacionalidade = "BRASIL";
        }
        //return $nacionalidades[strtoupper($nacionalidade)];
        //COLOCAR TODOS COM BRA PARA NACIONALIDADE (TEMPORARIO)
        return 'BRA';
    }

    private function getCodProgramaForm($curso)
    {
        $codigos = [
            91555  => 44,
            118468 => 95,
            118466 => 93,
            118470 => 94,
            91969  => 47,
            91561  => 45,
            91738  => 46,
        ];
        return $codigos[$curso->cod_curso];
    }

    private function getTurno($curso)
    {
        $turnos = [
            1 => 2, //matutino
            2 => 3, //vespertino
            3 => 4, //noturno
            4 => 1, //integral
        ];
        return $turnos[$curso->turno];
    }

    private function divirPorSemestre($cotas, $candidatosIngressantesCurso, $primeiroSemestre, $segundoSemestre, $deficiente)
    {
        foreach($cotas as $cota){
            $porCota = $candidatosIngressantesCurso->where('cota_vaga_ocupada_id', $cota->id);
            if($deficiente){
                if($cotas->first()->cod_cota == 'L9'){
                    $primeiroSemestre = $primeiroSemestre->concat($porCota);
                    $second = collect();
                    $segundoSemestre = $segundoSemestre->concat($second);
                }elseif($cotas->first()->cod_cota == 'L10'){
                    $first = collect();
                    $primeiroSemestre = $primeiroSemestre->concat($first);
                    $segundoSemestre = $segundoSemestre->concat($porCota);
                }
            }else{
                $metade = ceil($porCota->count()/2);
                $divisoes = $porCota->chunk($metade);

                if($divisoes->count()>0){
                    $first = $divisoes[0];
                }else{
                    $first = collect();
                }
                if($divisoes->count()>1){
                    $second = $divisoes[1];
                }else{
                    $second = collect();
                }

                if($first->count()!=$second->count()){
                    if($primeiroSemestre->count()<$segundoSemestre->count()){
                        $primeiroSemestre = $primeiroSemestre->concat($first);
                        $segundoSemestre = $segundoSemestre->concat($second);
                    }elseif($primeiroSemestre->count()>$segundoSemestre->count()){
                        $ultimoElemento = $first->slice($first->count()-1, 1)->first();
                        $first = $first->slice(0, -1);
                        $second->push($ultimoElemento);

                        $primeiroSemestre = $primeiroSemestre->concat($first);
                        $segundoSemestre = $segundoSemestre->concat($second);
                    }else{
                        $primeiroSemestre = $primeiroSemestre->concat($first);
                        $segundoSemestre = $segundoSemestre->concat($second);
                    }
                }else{
                    $primeiroSemestre = $primeiroSemestre->concat($first);
                    $segundoSemestre = $segundoSemestre->concat($second);
                }
            }
        }
        return array($primeiroSemestre, $segundoSemestre);
    }

    /**
     * Pega a string de ordenação garantindo que a coluna certa de ordenação irá ser passada.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return string $coluna
     */
    private function get_ordenacao(Request $request)
    {
        $coluna = 'name';
        switch ($request->ordenacao) {
            case 'nome':
                $coluna = 'name';
                break;
            case 'nota':
                $coluna = 'inscricaos.nu_nota_candidato';
                break;
        }
        return $coluna;
    }

    /**
     * Pega a string de ordem da coluna : ASC ou DESC.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return string $ordem
     */
    private function get_ordem(Request $request)
    {
        $ordem = 'ASC';
        switch ($request->ordenacao) {
            case 'nome':
                $ordem = 'ASC';
                break;
            case 'nota':
                $ordem = 'DESC';
                break;
        }
        return $ordem;
    }

    /**
     * Gera o arquivo pdf da listagem de pendencia e retorna o caminho do arquivo.
     *
     * @param  \App\Http\Requests\ListagemRequest  $request
     * @return string $caminho_do_arquivo
     */
    private function gerarListagemPendencia(ListagemRequest $request, Listagem $listagem)
    {
        $chamada = Chamada::find($request->chamada);
        $cursos = Curso::whereIn('id', $request->cursos)->orderBy('nome')->get();
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('id')->get();
        $ordenacao = $this->get_ordenacao($request);
        $ordem = $this->get_ordem($request);

        $inscricoes = collect();

        foreach ($cursos as $curso) {
            $inscricoes_curso = collect();
            if($curso->turno == Curso::TURNO_ENUM['matutino']){
                $turno = 'Matutino';
            }elseif($curso->turno == Curso::TURNO_ENUM['vespertino']){
                $turno = 'Vespertino';
            }elseif($curso->turno == Curso::TURNO_ENUM['noturno']){
                $turno = 'Noturno';
            }elseif($curso->turno == Curso::TURNO_ENUM['integral']){
                $turno = 'Integral';
            }
            if($cotas->where('cod_cota', 'A0')){
                $modalidadeCotaArray = [
                    'Ampla concorrência',
                    'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.',
                    'AMPLA CONCORRÊNCIA'
                ];
            }else{
                $modalidadeCotaArray = [];
            }

            $modalidadeCotaArray = array_merge($modalidadeCotaArray, $cotas->pluck('descricao')->toArray());
            $inscricoes_curso = Inscricao::select('inscricaos.*')->
                where([['curso_id', $curso->id], ['chamada_id', $chamada->id]])
                ->whereIn(
                    'no_modalidade_concorrencia',
                    $modalidadeCotaArray
                )
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('users','users.id','=','candidatos.user_id')
                ->orderBy($ordenacao, $ordem)
                ->get();

            if ($inscricoes_curso->count() > 0) {
                $inscricoes_curso = $inscricoes_curso->map->only(['id']);
                $inscricoes->push($inscricoes_curso);
            }
        }

        $pdf = PDF::loadView('listagem.pendencia', ['collect_inscricoes' => $inscricoes, 'chamada' => $chamada])->setPaper('a4', 'landscape');

        return $this->salvarListagem($listagem, $pdf->stream());
    }

    public function publicar(Request $request) {
        $listagem = Listagem::find($request->listagem_id);
        $listagem->publicada = $request->publicar;

        /*if ($listagem->job_batch_id == null && $listagem->enviaEmails()) {
            $batch = Bus::batch([
                new EnviarEmailsPublicacaoListagem($listagem),
            ])->name('Enviar e-mails da listagem id: '.$listagem->id)->dispatch();
            $listagem->job_batch_id = $batch->id;
        }*/

        return $listagem->save();
    }
}
