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
use App\Models\Listagem;
use App\Models\MultiplicadorVaga;
use App\Models\Sisu;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;

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

        if($chamada->regular){
            //$this->cadastrarCandidatosRegular($chamada);
            $batch = Bus::batch([
                new CadastroRegularCandidato($chamada),
            ])->name('Importar Chamada Regular '.$chamada->id)->dispatch();
            $chamada->job_batch_id = $batch->id;
        }else{

            $sisu = $chamada->sisu;
            if ($sisu->caminho_import_espera == null) {
                return redirect()->back()->withErrors(['error_espera' => 'Arquivo de espera ausente, envie a lista de espera e tente novamente.'])->withInput($request->all());
            }

            $this->salvarMultiplicadores($chamada, $request);
            //$this->cadastrarCandidatosEspera($chamada);

            $batch = Bus::batch([
                new CadastroListaEsperaCandidato($chamada),
            ])->name('Importar Chamada Lista Espera '.$chamada->id)->dispatch();
            $chamada->job_batch_id = $batch->id;
        }
        $chamada->update();
        return redirect(route('sisus.show', ['sisu' => $chamada->sisu->id]))->with(['success' => 'Realizando o cadastro. Aguarde...']);
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
                    'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
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
                    'status' => Inscricao::STATUS_ENUM['documentos_pendentes'],
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
        $concluidosPendentes = collect();
        $enviados = collect();
        $naoEnviados = collect();
        $invalidados = collect();

        $cursos = Curso::orderBy('nome')->get();
        $userPolicy = new UserPolicy();

        $L2 = Cota::where('cod_cota', 'L2')->first();
        $L6 = Cota::where('cod_cota', 'L6')->first();
        $L9 = Cota::where('cod_cota', 'L9')->first();
        $L10 = Cota::where('cod_cota', 'L10')->first();
        $L13 = Cota::where('cod_cota', 'L13')->first();
        $L14 = Cota::where('cod_cota', 'L14')->first();

        foreach($cursos as $curso){
            if($userPolicy->isAdminOrAnalistaGeral(auth()->user())){
                $candidatosConcluidos = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias']]])->get();
                $candidatosConcluidosPendencia = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_aceitos_com_pendencias']]])->get();
                $candidatosNaoEnviado = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_pendentes']]])->get();
                $candidatosEnviado = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_enviados']]])->get();
                $candidatosInvalidados = Inscricao::where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_invalidados']]])->get();
            }else if($userPolicy->soEhAnalistaHeteroidentificacao(auth()->user())){
                $retorno = $this->inscricoesHeteroidentificacao($chamada, $curso, [$L2->id, $L6->id, $L10->id, $L14->id], ['fotografia', 'heteroidentificacao', 'declaracao_cotista']);
                $candidatosConcluidos = $retorno['candidatosConcluidos'];
                $candidatosConcluidosPendencia = $retorno['candidatosConcluidosPendencia'];
                $candidatosNaoEnviado = $retorno['candidatosNaoEnviado'];
                $candidatosEnviado = $retorno['candidatosEnviado'];
                $candidatosInvalidados = $retorno['candidatosInvalidados'];
            }elseif($userPolicy->soEhAnalistaMedico(auth()->user())){
                $retorno = $this->inscricoesMedico($chamada, $curso, $L9, $L10, $L13, $L14);
                $candidatosConcluidos = $retorno['candidatosConcluidos'];
                $candidatosConcluidosPendencia = $retorno['candidatosConcluidosPendencia'];
                $candidatosNaoEnviado = $retorno['candidatosNaoEnviado'];
                $candidatosEnviado = $retorno['candidatosEnviado'];
                $candidatosInvalidados = $retorno['candidatosInvalidados'];
            }else if($userPolicy->ehAnalistaHeteroidentificacaoEMedico(auth()->user())){
                $inscricoes1 = $this->inscricoesHeteroidentificacao($chamada, $curso, [$L2->id, $L6->id], ['fotografia', 'heteroidentificacao', 'declaracao_cotista']);
                $candidatosConcluidos = $inscricoes1['candidatosConcluidos'];
                $candidatosConcluidosPendencia = $inscricoes1['candidatosConcluidosPendencia'];
                $candidatosNaoEnviado = $inscricoes1['candidatosNaoEnviado'];
                $candidatosEnviado = $inscricoes1['candidatosEnviado'];
                $candidatosInvalidados = $inscricoes1['candidatosInvalidados'];

                $inscricoes2 = $this->inscricoesHeteroidentificacao($chamada, $curso, [$L10->id, $L14->id], ['fotografia', 'heteroidentificacao', 'declaracao_cotista', 'laudo_medico']);
                $candidatosConcluidos = $candidatosConcluidos->concat($inscricoes2['candidatosConcluidos'])->unique();
                $candidatosConcluidosPendencia = $candidatosConcluidosPendencia->concat($inscricoes2['candidatosConcluidosPendencia'])->unique();
                $candidatosNaoEnviado = $candidatosNaoEnviado->concat($inscricoes2['candidatosNaoEnviado'])->unique();
                $candidatosEnviado = $candidatosEnviado->concat($inscricoes2['candidatosEnviado'])->unique();
                $candidatosInvalidados = $candidatosInvalidados->concat($inscricoes2['candidatosInvalidados'])->unique();

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
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->where(function($qry){
                $qry->whereIn('candidatos.cor_raca', [2, 3])
                    ->orWhereNull('candidatos.cor_raca');
            })
            ->whereIn('inscricaos.id', function($qry) use ($arquivos){
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
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->where(function($qry){
                $qry->whereIn('candidatos.cor_raca', [2, 3])
                    ->orWhereNull('candidatos.cor_raca');
            })->get();

        $retorno['candidatosNaoEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_pendentes']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->where(function($qry){
                $qry->whereIn('candidatos.cor_raca', [2, 3])
                    ->orWhereNull('candidatos.cor_raca');
            })->get();

        $retorno['candidatosEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_enviados']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->where(function($qry){
                $qry->whereIn('candidatos.cor_raca', [2, 3])
                    ->orWhereNull('candidatos.cor_raca');
            })
            ->whereNotIn('inscricaos.id', function($qry) use ($arquivos) {
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
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->where(function($qry){
                $qry->whereIn('candidatos.cor_raca', [2, 3])
                    ->orWhereNull('candidatos.cor_raca');
            })
            ->whereIn('inscricaos.id', function($qry) use ($arquivos) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', $arquivos)
                    ->whereIn('avaliacaos.avaliacao', [1, 2])
                    ->whereIn('inscricaos.id', function($sub) use ($arquivos){
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
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->whereIn('inscricaos.id', function($qry) {
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
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->get();

        $retorno['candidatosNaoEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_pendentes']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->get();

        $retorno['candidatosEnviado'] = Inscricao::select('inscricaos.*')
            ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id], ['status', Inscricao::STATUS_ENUM['documentos_enviados']]])
            ->whereIn('cota_id', $cotas)
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->whereNotIn('inscricaos.id', function($qry) {
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
            ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
            ->join('users','users.id','=','candidatos.user_id')
            ->whereIn('inscricaos.id', function($qry) {
                $qry->select('inscricaos.id')
                    ->from('inscricaos')
                    ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                    ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                    ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                    ->whereIn('avaliacaos.avaliacao', [1, 2])
                    ->whereIn('inscricaos.id', function($sub){
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
                    ->havingRaw('COUNT(*) = ?', [3])
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

        $userPolicy = new UserPolicy();
        $concluidos = collect();
        $invalidados = collect();
        if($userPolicy->isAdminOrAnalistaGeral(auth()->user())){
            $query = Inscricao::select('inscricaos.*')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('users','users.id','=','candidatos.user_id');
        }elseif($userPolicy->soEhAnalistaHeteroidentificacao(auth()->user())){
            $L2 = Cota::where('cod_cota', 'L2')->first();
            $L6 = Cota::where('cod_cota', 'L6')->first();
            $L10 = Cota::where('cod_cota', 'L10')->first();
            $L14 = Cota::where('cod_cota', 'L14')->first();
            $query = Inscricao::select('inscricaos.*')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('users','users.id','=','candidatos.user_id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id, $L10->id, $L14->id])
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                        ->orWhereNull('candidatos.cor_raca');
                });
            $concluidos = Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                    ->orWhereNull('candidatos.cor_raca');
                })
                ->whereIn('cota_id', [$L2->id, $L6->id, $L10->id, $L14->id])
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [3])
                ->get();
            $invalidados = Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id, $L10->id, $L14->id])
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                        ->orWhereNull('candidatos.cor_raca');
                })
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function($sub){
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
        }elseif($userPolicy->soEhAnalistaMedico(auth()->user())){
            $L9 = Cota::where('cod_cota', 'L9')->first();
            $L10 = Cota::where('cod_cota', 'L10')->first();
            $L13 = Cota::where('cod_cota', 'L13')->first();
            $L14 = Cota::where('cod_cota', 'L14')->first();
            $query = Inscricao::select('inscricaos.*')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L10->id, $L13->id, $L14->id])
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('users','users.id','=','candidatos.user_id');
            $concluidos = Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L10->id, $L13->id, $L14->id])
                ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [2])
                ->get();
            $invalidados = Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L10->id, $L13->id, $L14->id])
                ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function($sub){
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
        }elseif($userPolicy->ehAnalistaHeteroidentificacaoEMedico(auth()->user())){
            $L2 = Cota::where('cod_cota', 'L2')->first();
            $L6 = Cota::where('cod_cota', 'L6')->first();
            $L9 = Cota::where('cod_cota', 'L9')->first();
            $L10 = Cota::where('cod_cota', 'L10')->first();
            $L13 = Cota::where('cod_cota', 'L13')->first();
            $L14 = Cota::where('cod_cota', 'L14')->first();

            $query = Inscricao::select('inscricaos.*')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('users','users.id','=','candidatos.user_id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id, $L10->id, $L14->id])
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                        ->orWhereNull('candidatos.cor_raca');
                })
                ->get();
            $concluidos = Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                    ->orWhereNull('candidatos.cor_raca');
                })
                ->whereIn('cota_id', [$L2->id, $L6->id])
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [3])
                ->get();
            $invalidados = Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L2->id, $L6->id])
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                        ->orWhereNull('candidatos.cor_raca');
                })
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function($sub){
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

            $concluidos = $concluidos->concat(Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                        ->orWhereNull('candidatos.cor_raca');
                })
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L10->id, $L14->id])
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista', 'laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1])
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [4])
                ->get());
            $invalidados = $invalidados->concat(Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where(function($qry){
                    $qry->whereIn('candidatos.cor_raca', [2, 3])
                        ->orWhereNull('candidatos.cor_raca');
                })
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L10->id, $L14->id])
                ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista', 'laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function($sub){
                    $sub->select('inscricaos.id')
                        ->from('inscricaos')
                        ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                        ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                        ->whereIn('arquivos.nome', ['fotografia', 'heteroidentificacao', 'declaracao_cotista', 'laudo_medico', 'declaracao_cotista'])
                        ->whereIn('avaliacaos.avaliacao', [2])
                        ->groupBy('inscricaos.id')
                        ->get();
                })
                ->groupBy('inscricaos.id')
                ->havingRaw('COUNT(*) = ?', [4])
                ->get());

            $query = $query->concat(Inscricao::select('inscricaos.*')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('users','users.id','=','candidatos.user_id')
                ->get());
            $concluidos = $concluidos->concat(Inscricao::select('inscricaos.id')
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
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
                ->join('candidatos','inscricaos.candidato_id','=','candidatos.id')
                ->join('arquivos', 'arquivos.inscricao_id', '=', 'inscricaos.id')
                ->join('avaliacaos', 'avaliacaos.arquivo_id', '=', 'arquivos.id')
                ->where([['chamada_id', $chamada->id], ['curso_id', $curso->id]])
                ->whereIn('cota_id', [$L9->id, $L13->id])
                ->whereIn('arquivos.nome', ['laudo_medico', 'declaracao_cotista'])
                ->whereIn('avaliacaos.avaliacao', [1, 2])
                ->whereIn('inscricaos.id', function($sub){
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
        $candidatos = $chamada->inscricoes()->inRandomOrder()->limit((int)$quantidade/2)->get();
        foreach($candidatos as $candidato){
            $candidato->status = Inscricao::STATUS_ENUM['documentos_aceitos_sem_pendencias'];
            $candidato->cd_efetivado = Inscricao::STATUS_VALIDACAO_CANDIDATO['cadastro_validado'];

            $cotaRemanejamento = $candidato->cotaRemanejada;
            if($cotaRemanejamento != null){
                $cota = $cotaRemanejamento;
            }else{
                $cota = $candidato->cota;
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
