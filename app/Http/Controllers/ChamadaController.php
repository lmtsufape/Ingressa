<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadaRequest;
use App\Jobs\CadastroListaEsperaCandidato;
use App\Jobs\CadastroRegularCandidato;
use App\Models\Candidato;
use App\Models\Chamada;
use App\Models\Cota;
use App\Models\Curso;
use App\Models\DataChamada;
use App\Models\Inscricao;
use App\Models\MultiplicadorVaga;
use App\Models\Sisu;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Cast\Object_;

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
    public function show($id)
    {
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($id);
        $datas = $chamada->datasChamada;
        $listagens = $chamada->listagem;

        return view('chamada.show', compact('chamada', 'datas', 'listagens'))->with(['tipos' => DataChamada::TIPO_ENUM, 'cursos' => Curso::all(), 'cotas' => Cota::all()]);;
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
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($id);
        $sisu = Sisu::find($chamada->sisu_id);
        $chamada->delete();

        return redirect(route('sisus.show', ['sisu' => $sisu->id]))->with(['success' => 'Chamada deletada com sucesso!']);
    }

    public function importarCandidatos(Request $request, $sisu_id, $chamada_id)
    {
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($chamada_id);
        if($chamada->caminho_import_sisu_gestao != null){
            if (Storage::disk()->exists('public/' . $chamada->caminho_import_sisu_gestao)) {
                Storage::delete('public/' . $chamada->caminho_import_sisu_gestao);
            }
        }
        $arquivo = $request->arquivo;
        $path = 'sisu/'.$chamada->sisu->id.'/'.$chamada->id.'/';
        $nome = $arquivo->getClientOriginalName();
        Storage::putFileAs('public/'.$path, $arquivo, $nome);
        $chamada->caminho_import_sisu_gestao = $path . $nome;
        if($chamada->regular){
            //$this->cadastrarCandidatosRegular($chamada);
            $batch = Bus::batch([
                new CadastroRegularCandidato($chamada),
            ])->name('Importar Chamada Regular '.$chamada->id)->dispatch();
            $chamada->job_batch_id = $batch->id;
        }else{
            $this->salvarMultiplicadores($chamada, $request);
            //$this->cadastrarCandidatosEspera($chamada);

            $batch = Bus::batch([
                new CadastroListaEsperaCandidato($chamada),
            ])->name('Importar Chamada Lista Espera '.$chamada->id)->dispatch();
            $chamada->job_batch_id = $batch->id;
        }
        $chamada->update();
        return redirect(route('sisus.show', ['sisu' => $chamada->sisu->id]))->with(['success' => 'Candidatos importados com sucesso. Aguarde o cadastro!']);
    }

    private function salvarMultiplicadores($chamada, $request)
    {
        $data = $request->all();
        $cursos = Curso::orderBy('nome')->get();

        foreach($cursos as $curso){
            $multiplicadores = $data['multiplicadores_curso_'.$curso->id];
            $cotas = $data['cotas_id_'.$curso->id];

            foreach($multiplicadores as $i => $multiplicador){
                $cota_curso = $curso->cotas->where('id', $cotas[$i])->first()->pivot;
                $multi = new MultiplicadorVaga();
                $multi->chamada_id = $chamada->id;
                $multi->cota_curso_id = $cota_curso->id;
                $multi->multiplicador = $multiplicador;
                $multi->save();
            }

        }
    }

    private function cadastrarCandidatosRegular($chamada)
    {
        $this->authorize('isAdmin', User::class);
        $dados = fopen('storage/'.$chamada->caminho_import_sisu_gestao, "r");
        $primeira = true;
        ini_set('max_execution_time', 300);
        while ( ($data = fgetcsv($dados,";",";") ) !== FALSE ) {
            if($primeira){
                $primeira = false;
            }else{
                $inscricao = new Inscricao([
                    'status' => Inscricao::STATUS_ENUM['documentos_requeridos'],
                    'protocolo' => Hash::make($data[8].$chamada->id),
                    'nu_etapa' => $data[0],
                    'no_campus' => $data[1],
                    'co_ies_curso' => $data[2],
                    'no_curso' => $data[3],
                    'ds_turno' => $data[4],
                    'ds_formacao' => $data[5],
                    'qt_vagas_concorrencia' => $data[6],
                    'co_inscricao_enem' => $data[7],
                    'cd_efetivado' => false,
                    'tp_sexo' => $data[12],
                    'nu_rg' => $data[13],
                    'no_mae' => $data[14],
                    'ds_logradouro' => $data[15],
                    'nu_endereco' => $data[16],
                    'ds_complemento' => $data[17],
                    'sg_uf_inscrito' => $data[18],
                    'no_municipio' => $data[19],
                    'no_bairro' => $data[20],
                    'nu_cep' => $data[21],
                    'nu_fone1' => $data[22],
                    'nu_fone2' => $data[23],
                    'ds_email' => $data[24],
                    'nu_nota_l' => floatval(str_replace( ',', '.', $data[25])),
                    'nu_nota_ch' => floatval(str_replace( ',', '.', $data[26])),
                    'nu_nota_cn' => floatval(str_replace( ',', '.', $data[27])),
                    'nu_nota_m' => floatval(str_replace( ',', '.', $data[28])),
                    'nu_nota_r' => floatval(str_replace( ',', '.', $data[29])),
                    'co_curso_inscricao' => $data[30],
                    'st_opcao' => $data[31],
                    'no_modalidade_concorrencia' => $data[32],
                    'st_bonus_perc' => $data[33],
                    'qt_bonus_perc' => $data[34],
                    'no_acao_afirmativa_bonus' => $data[35],
                    'nu_nota_candidato' => floatval(str_replace( ',', '.', $data[36])),
                    'nu_notacorte_concorrida' => floatval(str_replace( ',', '.', $data[37])),
                    'nu_classificacao' => intval($data[38]),
                    'ds_matricula' => $data[39],
                    'dt_operacao' => $data[40],
                    'co_ies' => $data[41],
                    'no_ies' => $data[42],
                    'sg_ies' => $data[43],
                    'sg_uf_ies' => $data[44],
                    'st_lei_optante' => $data[45],
                    'st_lei_renda' => $data[46],
                    'st_lei_etnia_p' => $data[47],
                    'st_lei_etnia_i' => $data[48],
                ]);

                dd($inscricao);

                $candidatoExistente = Candidato::where('nu_cpf_inscrito', $data[10])->first();
                if($candidatoExistente == null){
                    $user = new User([
                        'name' => $data[8],
                        'password' => Hash::make('12345678'),
                        'role' => User::ROLE_ENUM['candidato'],
                        'primeiro_acesso' => true,
                    ]);
                    if($data[9] != null){
                        $user->name = $data[9];
                    }
                    $user->save();

                    $candidato = new Candidato([
                        'nu_cpf_inscrito' => $data[10],
                        'dt_nascimento' => $data[11],
                    ]);
                    $candidato->user_id = $user->id;
                    $candidato->save();

                    $inscricao->chamada_id = $chamada->id;
                    $inscricao->candidato_id = $candidato->id;
                    $inscricao->save();

                }else{
                    if($data[9] != null){
                        $candidatoExistente->user->name = $data[9];
                    }else{
                        $candidatoExistente->user->name = $data[8];
                    }
                    $candidatoExistente->user->update();

                    $inscricao->chamada_id = $chamada->id;
                    $inscricao->candidato_id = $candidatoExistente->id;
                    $inscricao->save();

                }
            }
        }
    }

    public function cadastrarCandidatosEspera($chamada)
    {
        $this->authorize('isAdmin', User::class);
        $dados = fopen('storage/'.$chamada->caminho_import_sisu_gestao, "r");
        $primeira = true;
        ini_set('max_execution_time', 300);
        $candidatos = collect();
        $cont = 0;
        while ( ($data = fgetcsv($dados,";",';') ) !== FALSE ) {
            /*if($cont > 200){
                break;
            }*/
            if($primeira){
                $primeira = false;
            }else{
                //Armazenamos as informações de cada candidato
                $inscricao = array(
                    'status' => Inscricao::STATUS_ENUM['documentos_requeridos'],
                    'protocolo' => Hash::make($data[8].$chamada->id),
                    'nu_etapa' => $data[0],
                    'no_campus' => $data[1],
                    'co_ies_curso' => $data[2],
                    'no_curso' => $data[3],
                    'ds_turno' => $data[4],
                    'ds_formacao' => $data[5],
                    'qt_vagas_concorrencia' => $data[6],
                    'co_inscricao_enem' => $data[7],

                    'no_inscrito' => $data[8],
                    'no_social' => $data[9],
                    'nu_cpf_inscrito' => $data[10],
                    'dt_nascimento' => $data[11],

                    'cd_efetivado' => false,
                    'tp_sexo' => $data[12],
                    'nu_rg' => $data[13],
                    'no_mae' => $data[14],
                    'ds_logradouro' => $data[15],
                    'nu_endereco' => $data[16],
                    'ds_complemento' => $data[17],
                    'sg_uf_inscrito' => $data[18],
                    'no_municipio' => $data[19],
                    'no_bairro' => $data[20],
                    'nu_cep' => $data[21],
                    'nu_fone1' => $data[22],
                    'nu_fone2' => $data[23],
                    'ds_email' => $data[24],
                    'nu_nota_l' => floatval($data[25]),
                    'nu_nota_ch' => floatval($data[26]),
                    'nu_nota_cn' => floatval($data[27]),
                    'nu_nota_m' => floatval($data[28]),
                    'nu_nota_r' => floatval($data[29]),
                    'co_curso_inscricao' => $data[30],
                    'st_opcao' => $data[31],
                    'no_modalidade_concorrencia' => $data[32],
                    'st_bonus_perc' => $data[33],
                    'qt_bonus_perc' => $data[34],
                    'no_acao_afirmativa_bonus' => $data[35],
                    'nu_nota_candidato' => floatval($data[36]),
                    'nu_notacorte_concorrida' => floatval($data[37]),
                    'nu_classificacao' => intval($data[38]),
                    'ds_matricula' => $data[39],
                    'dt_operacao' => $data[40],
                    'co_ies' => $data[41],
                    'no_ies' => $data[42],
                    'sg_ies' => $data[43],
                    'sg_uf_ies' => $data[44],
                    'st_lei_optante' => $data[45],
                    'st_lei_renda' => $data[46],
                    'st_lei_etnia_p' => $data[47],
                    'st_lei_etnia_i' => $data[48],
                );
                $candidatos->push($inscricao);
                $cont += 1;
            }
        }


        //Agrupamos por curso
        $grouped = $candidatos->groupBy(function ($candidato) {
            return $candidato['co_ies_curso'].$candidato['ds_turno'];
        });
        $porCurso = collect();
        //E separamos por modalidade
        foreach($grouped as $curso){
            $porCurso->push($curso->groupBy('no_modalidade_concorrencia'));
        }

        $cursos = collect();
        $cotasCursosCOD = collect();

        //Feito isto, é necessário juntar todos os inscritos da ampla concorrencia independete da cota de 10%
        foreach($porCurso as $curso){
            $modalidade = collect();
            $ampla = collect();
            $modalidades = collect();

            $cotaCOD = collect();
            $cotaAmpla = false;
            foreach($curso as $porModalidade){
                //os de ampla são colocados em um único collection aqui
                if($porModalidade[0]['no_modalidade_concorrencia'] == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
                    $porModalidade[0]['no_modalidade_concorrencia'] == 'AMPLA CONCORRÊNCIA' || $porModalidade[0]['no_modalidade_concorrencia'] == 'Ampla concorrência'){
                    $ampla = $ampla->concat($porModalidade);
                    if(!$cotaAmpla){
                        $cotaAmpla = true;
                    }
                }else{
                    $modalidade = $porModalidade;
                    if(!$cotaCOD->contains($modalidade[0]['no_modalidade_concorrencia'])){
                        $cotaCOD->push($modalidade[0]['no_modalidade_concorrencia']);
                    }
                    $modalidade = $modalidade->sortBy(function($candidato){
                        return $candidato['nu_nota_candidato'];
                    });
                    $modalidades->push($modalidade);
                }
            }
            //ordenamos os inscritos da modalidade daquele curso pela nota
            $ampla = $ampla->sortByDesc(function($candidato){
                return $candidato['nu_nota_candidato'];
            });
            $modalidades->push($ampla);
            $cursos->push($modalidades);
            if($cotaAmpla){
                $cotaCOD->push('Ampla concorrência');
            }
            $cotasCursosCOD->push($cotaCOD);

        }

        //Preparados os dados dos inscritos, agora criaremos as instancias para salvar no banco
        //Percorremos cada curso
        foreach($cursos as $indexCurso => $curso){
            $candidato = $curso[0][0];

            /*//Recuperamos a cota que aquele inscrito está relacionado
            if($candidato['no_modalidade_concorrencia'] == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' ||
            $candidato['no_modalidade_concorrencia'] == 'Ampla concorrência' || $candidato['no_modalidade_concorrencia'] == 'AMPLA CONCORRÊNCIA'){
                $cota = Cota::where('descricao',  'Ampla concorrência')->first();
            }else{
                $cota = Cota::where('descricao', $candidato['no_modalidade_concorrencia'])->first();
            }
            //E pegamos a informação de quantas vagas temos tem restante baseado em quantos candidatos foram efetivados e quantas vagas são
            //ofertadas para aquela cota*/

            if($candidato['ds_turno'] == 'Matutino'){
                $turno =  Curso::TURNO_ENUM['matutino'];
            }elseif($candidato['ds_turno'] == 'Vespertino'){
                $turno = Curso::TURNO_ENUM['vespertino'];
            }elseif($candidato['ds_turno'] == 'Noturno'){
                $turno = Curso::TURNO_ENUM['noturno'];
            }elseif($candidato['ds_turno'] == 'Integral'){
                $turno = Curso::TURNO_ENUM['integral'];
            }

            //E recuperamos a instancia do curso do banco de dados
            $curs = Curso::where([['cod_curso', $candidato['co_ies_curso']], ['turno', $turno]])->first();

            //Varremos todas as cotas do curso
            foreach($curs->cotas as $cota){

                //recuperamos informações da quantidade que iremos chamar
                $cota_curso = $curs->cotas()->where('cota_id', $cota->id)->first()->pivot;

                $vagasCota = $cota_curso->quantidade_vagas - $cota_curso->vagas_ocupadas;
                //chamamos o número de vagas disponíveis vezes o valor do multiplicador passado
                $multiplicador = MultiplicadorVaga::where('cota_curso_id', $cota_curso->id)->first();
                if($multiplicador != null){
                    $vagasCota *= $multiplicador->multiplicador;
                }

                //aqui veremos se essa cota tem candidatos inscritos para fazer o cadastro
                $cursoAtual = $cotasCursosCOD[$indexCurso];

                $modalidadeDaCotaIndex = null;

                //Se o curso atual possuir algum candidato da modalidade descrita na descricao da cota, significa que temos quem chamar
                foreach($cursoAtual as $index => $modalidadeCursoAtual){
                    if($modalidadeCursoAtual == $cota->descricao){
                        $modalidadeDaCotaIndex = $index;
                        break;
                    }

                }
                //dd($modalidadeDaCotaIndex);

                //Então assim faremos
                if($cota->descricao == 'Ampla concorrência' && $curs->id == 1){
                    dd($cursos[$indexCurso][$modalidadeDaCotaIndex]);

                }

                //Caso restem vagas, faremos o remanejamento
                if($vagasCota < 0){
                    foreach($cota->remanejamentos as $remanejamento){
                        $proximaCota = $remanejamento->proximaCota;
                        $cursoAtual = $cotasCursosCOD[$indexCurso];

                        $modalidadeDaCotaIndex = null;

                        foreach($cursoAtual as $indexRemanejamento => $modalidadeCursoAtualRemanejamento){
                            if($modalidadeCursoAtualRemanejamento == $proximaCota->descricao){
                                $modalidadeDaCotaIndex = $indexRemanejamento;
                                break;
                            }
                        }
                        if($modalidadeDaCotaIndex != null){
                            //$vagasCota = $this->fazerCadastro($proximaCota, $curs, $cursos[$indexCurso][$modalidadeDaCotaIndex], $vagasCota);
                        }
                        if($vagasCota == 0){
                            break;
                        }
                    }
                }
            }
        }
    }

    public function candidatosChamada($sisu_id, $chamada_id)
    {
        $chamada = Chamada::find($chamada_id);
        $this->authorize('isAdminOrAnalista', User::class);
        $concluidos = collect();
        $chamados = collect();
        $cursos = Curso::orderBy('nome')->get();
        foreach($cursos as $curso){
            $candidatosConcluidos = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos']]])->get();
            $candidatosChamados = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])->get();

            $chamados->push(count($candidatosChamados));
            $concluidos->push(count($candidatosConcluidos));
        }

        return view('chamada.candidatos-chamada', compact('chamada', 'cursos', 'concluidos', 'chamados'))->with(['turnos' => Curso::TURNO_ENUM, 'graus' => Curso::GRAU_ENUM]);
    }

    public function candidatosCurso($sisu_id, $chamada_id, $curso_id)
    {
        $this->authorize('isAdminOrAnalista', User::class);
        $chamada = Chamada::find($chamada_id);
        $curso = Curso::find($curso_id);

        $turno = $curso->getTurno();
        $candidatos = Inscricao::select('inscricaos.*')->
        where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->orderBy('name')
            ->get();
        return view('chamada.candidatos-curso', compact('chamada', 'curso', 'candidatos', 'turno'));
    }

    public function aprovarCandidatosChamada($sisu_id, $chamada_id)
    {
        $this->authorize('isAdmin', User::class);
        $chamada = Chamada::find($chamada_id);
        $quantidade = $chamada->inscricoes()->get()->count();
        $candidatos = $chamada->inscricoes()->inRandomOrder()->limit((int)$quantidade/2)->get();
        foreach($candidatos as $candidato){
            $candidato->status = Inscricao::STATUS_ENUM['documentos_aceitos'];
            $candidato->cd_efetivado = true;

            if($candidato->no_modalidade_concorrencia == 'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' || $candidato->no_modalidade_concorrencia == 'Ampla concorrência' || $candidato->no_modalidade_concorrencia == 'AMPLA CONCORRÊNCIA'){
                $cota = Cota::where('descricao',  'Ampla concorrência')->first();
            }else{
                $cota = Cota::where('descricao', $candidato->no_modalidade_concorrencia)->first();
            }
            if($cota == null){
                dd($candidato->no_modalidade_concorrencia);
            }

            $curso = $candidato->curso;
            if($curso == null){
                dd($candidato->cod_ies_curso);
            }

            $cota_curso = $curso->cotas()->where('cota_id', $cota->id)->first()->pivot;
            $cota_curso->vagas_ocupadas += 1;

            $candidato->update();
            $cota_curso->update();

        }
        return redirect()->back()->with(['success' => 'Candidatos efetivados com sucesso.']);
    }
}
