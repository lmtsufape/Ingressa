<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListagemRequest;
use App\Models\Listagem;
use Illuminate\Http\Request;
use App\Models\Inscricao;
use App\Models\Cota;
use App\Models\Curso;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Chamada;
use Illuminate\Support\Facades\Storage;

class ListagemController extends Controller
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
    public function store(ListagemRequest $request)
    {
        set_time_limit(300);
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
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('nome')->get();
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
                    $ampla2 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'Ampla concorrência'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla2);

                    $ampla3 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla3);


                    $ampla4 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'AMPLA CONCORRÊNCIA'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla4);

                    $ampla = $ampla->sortBy(function($inscrito){
                        return $inscrito->candidato->user->name;
                    });
                }else if($cota->getCodCota() == Cota::COD_COTA_ENUM['B4342']){
                    //ignorar a de 10% visto que entra na mesma tabela que A0
                }else{
                    $inscritosCota = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', $cota->getCodCota()], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    if($inscritosCota->count() > 0 ){
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
        $chamada = Chamada::find($request->chamada);
        $cursos = Curso::whereIn('id', $request->cursos)->orderBy('nome')->get();
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('nome')->get();
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
                    $ampla2 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'Ampla concorrência'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla2);

                    $ampla3 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla3);


                    $ampla4 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'AMPLA CONCORRÊNCIA'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla4);

                    $ampla = $ampla->sortBy(function($inscrito){
                        return $inscrito->candidato->user->name;
                    });
                }else if($cota->getCodCota() == Cota::COD_COTA_ENUM['B4342']){
                    //ignorar a de 10% visto que entra na mesma tabela que A0
                }else{
                    $inscritosCota = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', $cota->getCodCota()], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    if($inscritosCota->count() > 0 ){
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
        $pdf = PDF::loadView('listagem.resultado', ['collect_inscricoes' => $inscricoes, 'chamada' => $chamada]);

        return $this->salvarListagem($listagem, $pdf->stream());
    }

    private function gerarListagemFinal(ListagemRequest $request, Listagem $listagem)
    {
        $chamada = Chamada::find($request->chamada);
        $sisu = $chamada->sisu;
        $cursos = Curso::all();
        $cotas = Cota::all();
        $candidatosIngressantesCursos = collect();
        $candidatosReservaCursos = collect();
        dd($chamada);

        foreach($cursos as $curso){
            $candidatosIngressantesCurso = collect();
            $candidatosReservaCurso = collect();

            foreach($cotas as $cota){
                $candidatosCotaCurso = Inscricao::where([['sisu_id', $sisu->id], ['curso_id', $curso->id],
                ['cota_vaga_ocupada_id', $cota->id], ['cd_efetivado', Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado']]])->get();

                $cota_curso_quantidade = $curso->cotas()->where('cota_id', $cota->id)->first()->pivot->quantidade_vagas;

                foreach($candidatosCotaCurso as $candidato){
                    if($cota_curso_quantidade > 0){
                        $candidatosIngressantesCurso->push($candidato);
                        $cota_curso_quantidade -= 1;
                    }else{
                        $candidatosReservaCurso->push($candidato);
                    }
                }
            }
        }
        
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
        $cotas = Cota::whereIn('id', $request->cotas)->orderBy('nome')->get();
        $ordenacao = $this->get_ordenacao($request);
        $ordem = $this->get_ordem($request);

        $inscricoes = collect();

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
                    $ampla2 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'Ampla concorrência'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                    ->orWhere([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'Ampla concorrência'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla2);

                    $ampla3 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                    ->orWhere([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla3);


                    $ampla4 = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'AMPLA CONCORRÊNCIA'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                    ->orWhere([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', 'AMPLA CONCORRÊNCIA'], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    $ampla = $ampla->concat($ampla4);

                    $ampla = $ampla->sortBy(function($inscrito){
                        return $inscrito->candidato->user->name;
                    });
                }else if($cota->getCodCota() == Cota::COD_COTA_ENUM['B4342']){
                    //ignorar a de 10% visto que entra na mesma tabela que A0
                }else{
                    $inscritosCota = Inscricao::select('inscricaos.*')->
                    where([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', $cota->getCodCota()], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                    ->orWhere([['co_curso_inscricao', $curso->cod_curso], ['no_modalidade_concorrencia', $cota->getCodCota()], ['chamada_id', $chamada->id], ['ds_turno', $turno]])
                        ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                        ->join('users','users.id','=','candidatos.user_id')
                        ->orderBy($ordenacao, $ordem)
                        ->get();
                    if($inscritosCota->count() > 0 ){
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

        $pdf = PDF::loadView('listagem.pendencia', ['collect_inscricoes' => $inscricoes, 'chamada' => $chamada]);

        return $this->salvarListagem($listagem, $pdf->stream());
    }
}
