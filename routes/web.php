<?php

use App\Http\Controllers\ChamadaController;
use App\Http\Controllers\SisuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\CotaController;
use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\DataChamadaController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ListagemController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\App;

include "fortify.php";
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomeController::class, 'index'])->name('index');
Route::get('/login', [WelcomeController::class, 'login'])->name('logar');
Route::get('/sobre', [WelcomeController::class, 'sobre'])->name('sobre');
Route::get('/edicoes', [WelcomeController::class, 'edicoes'])->name('edicoes');
Route::get('/edicoes/{id}', [WelcomeController::class, 'showEdicao'])->name('edicoes.show');
Route::get('/contato', [WelcomeController::class, 'contato'])->name('contato');
Route::post('/contato/enviar', [WelcomeController::class, 'enviarMensagem'])->name('enviar.mensagem');
Route::get('/informacoes/enviar-docs', [WelcomeController::class, 'envio_docs'])->name('envio.docs');

Route::get('/primeiro-acesso', [CandidatoController::class, 'prepararAdicionar'])->name('primeiro.acesso');
Route::post('/verificacao', [CandidatoController::class, 'verificacao'])->name('primeiroAcesso.verificacao');
Route::get('/editar', [CandidatoController::class, 'editarAcesso'])->name('primeiroAcesso.editar');
Route::post('/atualizar', [UserController::class, 'update'])->name('primeiroAcesso.atualizar');

Route::middleware(['auth:sanctum', 'verified', 'atualizar_dados'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::put('candidatos/{candidato}/inscricoes/{inscricao}', [CandidatoController::class, 'update'])->name('candidato.atualizar');
Route::get('candidatos/{candidato}/inscricoes/{inscricao}', [CandidatoController::class, 'edit'])->name('candidato.edit');
Route::middleware(['auth:sanctum', 'verified', 'atualizar_dados'])->group(function () {

    Route::resource('usuarios', UserController::class);
    Route::post('/usuarios/update-analista', [UserController::class, 'updateAnalista'])
        ->name('usuarios.update.analista');

    Route::resource('sisus', SisuController::class);

    Route::resource('chamadas', ChamadaController::class)->except([
        'create'
    ]);

    Route::get('/sisus/{sisu_id}/criar-chamada', [ChamadaController::class, 'create'])
        ->name('chamadas.create');

    Route::post('/sisus/{sisu_id}/importar-candidatos/{chamada_id}', [ChamadaController::class, 'importarCandidatos'])
        ->name('chamadas.importar.candidatos');

    Route::post('/sisus/{sisu_id}/importa-planilha-regular', [SisuController::class, 'importarPlanilhasRegular'])
        ->name('chamadas.importar.planilhas.regular');

    Route::post('/sisus/{sisu_id}/importa-planilha-espera', [SisuController::class, 'importarPlanilhasEspera'])
        ->name('chamadas.importar.planilhas.espera');

    Route::get('/sisus/{sisu_id}/chamada/{chamada_id}/candidatos-chamada', [ChamadaController::class, 'candidatosChamada'])
        ->name('chamadas.candidatos');

    Route::get('/sisus/{sisu_id}/chamada/{chamada_id}/candidatos-chamada/curso/{curso_id}', [ChamadaController::class, 'candidatosCurso'])
        ->name('chamadas.candidatos.curso');

    Route::post('/sisus/{sisu_id}/chamada/{chamada_id}/candidatos-chamada/curso/{curso_id}/efetivar', [InscricaoController::class, 'updateStatusEfetivado'])
        ->name('inscricao.status.efetivado');

    Route::post('/sisus/{sisu_id}/chamada/{chamada_id}/candidatos-chamada/curso/{curso_id}/bloquear', [InscricaoController::class, 'bloquearInscricao'])
        ->name('inscricao.bloquear.inscricao');

    Route::post('/sisus/{sisu_id}/chamada/{chamada_id}/candidatos-chamada/curso/{curso_id}/confirmar-invalidacao', [InscricaoController::class, 'confirmarInvalidacao'])
        ->name('inscricao.confirmar.invalidacao');

    if (App::environment(['test', 'local'])) {
        Route::get('/sisus/{sisu_id}/chamada/{chamada_id}/candidatos-chamada-aprovar', [ChamadaController::class, 'aprovarCandidatosChamada'])
            ->name('chamadas.candidatos.aprovar');
    }

    Route::get('/sisus/{sisu_id}/lista-personalizada-cursos', [ListagemController::class, 'listaPersonalizada'])
        ->name('lista.personalizada');

    Route::get('/sisus/{sisu_id}/lista-personalizada-cursos/{curso_id}', [ListagemController::class, 'listaPersonalizadaCurso'])
        ->name('lista.personalizada.curso');

    Route::post('/sisus/{sisu_id}/resetar-lista-personalizada', [ListagemController::class, 'resetarListaPersonalizada'])
        ->name('resetar.lista.personalizada');

    Route::resource('cursos', CursoController::class);

    Route::resource('cotas', CotaController::class);

    Route::resource('datas', DataChamadaController::class);
    Route::resource('listagems', ListagemController::class);

    Route::get('/listagem/{chamada}/export', [ListagemController::class, 'exportarCSV'])->name('exportar-ingressantes');
    Route::get('/sisus/{id}/export-siga', [ListagemController::class, 'exportarSigaPersonalizado'])->name('exportar-ingressantes-personalizado');
    Route::get('/sisus/{id}/lista-final', [ListagemController::class, 'gerarListagemFinalPersonalizada'])->name('gerar-lista-final-personalizada');
    Route::get('/chamada/{chamada}/export-sisu-getsao', [ChamadaController::class, 'exportarCSVSisuGestao'])->name('exportar-sisu-gestao');
    Route::get('/chamada/{chamada}/exportar-ingressantes-reserva', [ListagemController::class, 'exportarIngressantesEspera'])->name('exportar-ingressantes-reserva');

    Route::resource('inscricaos', InscricaoController::class);
    Route::get('/inscricaos/{inscricao_id}/documentacao', [InscricaoController::class, 'showInscricaoDocumentacao'])->name('inscricao.documentacao');
    Route::post('/inscricaos/{inscricao_id}/enviar-documentos', [InscricaoController::class, 'enviarDocumentos'])->name('inscricao.enviar.documentos');
    Route::post('/inscricaos/{inscricao_id}/analisar-documentos', [InscricaoController::class, 'analisarDocumentos'])->name('inscricao.analisar.documentos');
    Route::post('/inscricaos/{inscricao_id}/avaliar-documento', [InscricaoController::class, 'avaliarDocumento'])->name('inscricao.avaliar.documento');
    Route::post('/inscricaos/{inscricao_id}/modificar-comentario', [InscricaoController::class, 'modificarComentario'])->name('inscricao.modificar.comentario');
    Route::post('/inscricaos/{inscricao_id}/status-desistencia', [InscricaoController::class, 'statusDesistencia'])->name('inscricao.status-desistencia');

    Route::get('/inscricaos/{inscricao_id}/ver-documento/{documento_nome}', [InscricaoController::class, 'showDocumento'])->name('inscricao.arquivo');
    Route::get('/sisus/{sisu_id}/chamada/{chamada_id}/candidatos-chamada/curso/{curso_id}/inscricao/{inscricao_id}', [InscricaoController::class, 'showAnalisarDocumentos'])
        ->name('inscricao.show.analisar.documentos');
    Route::post('/enviar/email/candidato', [CandidatoController::class, 'enviarEmail'])->name('enviar.email.candidato');

    Route::get('/inscricao/get-documento', [InscricaoController::class, 'inscricaoDocumentoAjax'])->name('inscricao.documento.ajax');
    Route::get('/inscricao/get-prox-documento', [InscricaoController::class, 'inscricaoProxDocumentoAjax'])->name('inscricao.documento.proximo');
    Route::get('/inscricao/download-documentos/inscricao/{inscricao_id}', [InscricaoController::class, 'downloadDocumentosCandidato'])->name('baixar.documentos.candidato');

    Route::get('cotas/{cota}/remanejamento', [CotaController::class, 'remanejamento'])->name('cotas.remanejamento');

    Route::post('cotas/{cota}/remanejamento/atualizar', [CotaController::class, 'remanejamentoUpdate'])->name('cotas.remanejamento.update');

    Route::put('/curso/info', [CursoController::class, 'updateAjax'])->name('cursos.update.ajax');

    Route::get('/curso/info', [CursoController::class, 'infoCurso'])->name('cursos.info.ajax');
    Route::get('/cursos/{curso_id}/chamada/{chamada_id}/download-documentos', [CursoController::class, 'downloadDocumentosTodosCandidatos'])->name('baixar.documentos.candidatos.curso');

    Route::get('/cota/info', [CotaController::class, 'infoCota'])->name('cota.info.ajax');
    Route::put('/cota/update/modal', [CotaController::class, 'updateModal'])->name('cotas.update.modal');

    Route::get('/usuario/info', [UserController::class, 'infoUser'])->name('usuario.info.ajax');

    Route::get('/listagens/publicar', [ListagemController::class, 'publicar'])->name('publicar.listagem');

    Route::post('/inscricaos/{inscricao_id}/editar-situacao-lista', [InscricaoController::class, 'editarSituacao'])->name('inscricao.situacao.update');
});

Route::get('/test/{id}', function ($id) {
    $chamada = \App\Models\Chamada::find($id);

    $csvPath = storage_path('app' . DIRECTORY_SEPARATOR . $chamada->sisu->caminho_import_espera);

    // Lendo o arquivo CSV
    $csv = \League\Csv\Reader::createFromPath($csvPath, 'r');
    $csv->setDelimiter(';');
    $csv->setHeaderOffset(0);
    $records = $csv->getRecords();

    // Arrays para armazenar os dados dos usuários, candidatos e inscrições
    $usersData = [];
    $candidatosData = [];
    $inscricoesData = [];

    // Otimização para pegar apenas os candidatos que já estão cadastrados e usar indexação para tornar a busca mais rápida
    $cpfInscritos = array_column(iterator_to_array($records), 'NU_CPF_INSCRITO');
    $candidatos = \App\Models\Candidato::whereIn('nu_cpf_inscrito', $cpfInscritos)
        ->with('user')
        ->get()
        ->keyBy('nu_cpf_inscrito');


    // Pega o próximo valor da sequência para que seja possível inserir os ids sem usar o método create ou save dentro do foreach
    $nextUserIdValue = \Illuminate\Support\Facades\DB::select("SELECT nextval('users_id_seq')")[0]->nextval;
    $nextCandidatoIdValue = \Illuminate\Support\Facades\DB::select("SELECT nextval('candidatos_id_seq')")[0]->nextval;

    foreach ($records as $record) {
        $candidato = $candidatos->get($record['NU_CPF_INSCRITO']);

        // Cria um novo candidato e usuário caso ele não exista
        if (!$candidato) {
            // Adiciona o usuário no array para inserção
            $usersData[] = [
                'id' =>  $nextUserIdValue,
                'name' => empty($record['NO_SOCIAL']) ? $record['NO_INSCRITO'] : $record['NO_SOCIAL'],
                'password' => '', // A senha será modificada quando o usuário acessar a conta pela primeira vez
                'role' => \App\Models\User::ROLE_ENUM['candidato'],
                'primeiro_acesso' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Adiciona o candidato no array para inserção
            $candidatosData[] = [
                'id' => $nextCandidatoIdValue,
                'no_social' => $record['NO_SOCIAL'],
                'no_inscrito' => $record['NO_INSCRITO'],
                'nu_cpf_inscrito' => $record['NU_CPF_INSCRITO'],
                'dt_nascimento' => (new DateTime($record['DT_NASCIMENTO']))->format('Y-m-d'),
                'etnia_e_cor' => \App\Models\Candidato::ETNIA_E_COR[$record['COR_RAÇA']],
                'user_id' => $nextUserIdValue++,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Atualiza dados do candidato caso ele exista
        } else {
            $candidatosData[] = [
                'id' => $candidato->id,
                'atualizar_dados' => true,
                'no_social' => $record['NO_SOCIAL'],
                'updated_at' => now(),

                // Os campos abaixo não serão atualizados, mas precisam ser passados para o método upsert por conta do funcionamento interno do postgres
                'user_id' => 0,
                'no_inscrito' => '',
                'nu_cpf_inscrito' => '',
                'dt_nascimento' => now(),
                'etnia_e_cor' => 0,
            ];

            $usersData[] = [
                'id' => $candidato->user->id,
                'name' => empty($record['NO_SOCIAL']) ? $record['NO_INSCRITO'] : $record['NO_SOCIAL'],
                'updated_at' => now(),

                // Os campos abaixo não serão atualizados, mas precisam ser passados para o método upsert por conta do funcionamento interno do postgres
                'password' => '',
                'role' => 0,
                'primeiro_acesso' => true,
            ];
        }

        // Adicionando inscrição apenas se o candidato não existir ou se ele não estiver inscrito nessa chamada do SiSU
        if (!$candidato || !$candidato->inscricoes()->where('sisu_id', $chamada->sisu->id)->exists()) {
            $inscricoesData[] = [
                'status' => \App\Models\Inscricao::STATUS_ENUM['documentos_pendentes'],
                'protocolo' => '',
                'nu_etapa' => $record['NU_ETAPA'],
                'no_campus' => $record['NO_CAMPUS'],
                'co_ies_curso' => $record['CO_IES_CURSO'],
                'no_curso' => $record['NO_CURSO'],
                'ds_turno' => $record['DS_TURNO'],
                'ds_formacao' => $record['DS_FORMACAO'],
                'qt_vagas_concorrencia' => $record['QT_VAGAS_CONCORRENCIA'],
                'co_inscricao_enem' => $record['CO_INSCRICAO_ENEM'],
                'tp_sexo' => $record['TP_SEXO'],
                'nu_rg' => $record['NU_RG'],
                'no_mae' => $record['NO_MAE'],
                'ds_logradouro' => $record['DS_LOGRADOURO'],
                'nu_endereco' => $record['NU_ENDERECO'],
                'ds_complemento' => $record['DS_COMPLEMENTO'],
                'sg_uf_inscrito' => $record['SG_UF_INSCRITO'],
                'no_municipio' => $record['NO_MUNICIPIO'],
                'no_bairro' => $record['NO_BAIRRO'],
                'nu_cep' => $record['NU_CEP'],
                'nu_fone1' => $record['NU_FONE1'],
                'nu_fone2' => $record['NU_FONE2'],
                'ds_email' => $record['DS_EMAIL'],
                'nu_nota_l' => str_replace(',', '.', $record['NU_NOTA_L']),
                'nu_nota_ch' => str_replace(',', '.', $record['NU_NOTA_CH']),
                'nu_nota_cn' => str_replace(',', '.', $record['NU_NOTA_CN']),
                'nu_nota_m' => str_replace(',', '.', $record['NU_NOTA_M']),
                'nu_nota_r' => str_replace(',', '.', $record['NU_NOTA_R']),
                'co_curso_inscricao' => $record['CO_CURSO_INSCRICAO'],
                'st_opcao' => $record['ST_OPCAO'],
                'no_modalidade_concorrencia' => $record['NO_MODALIDADE_CONCORRENCIA'],
                'st_bonus_perc' => $record['ST_BONUS_PERC'],
                'qt_bonus_perc' => $record['QT_BONUS_PERC'],
                'no_acao_afirmativa_bonus' => $record['NO_ACAO_AFIRMATIVA_BONUS'],
                'nu_nota_candidato' => str_replace(',', '.', $record['NU_NOTA_CANDIDATO']),
                'nu_notacorte_concorrida' => str_replace(',', '.', $record['NU_NOTACORTE_CONCORRIDA']),
                'nu_classificacao' => $record['NU_CLASSIFICACAO'],
                'ds_matricula' => $record['DS_MATRICULA'],
                'dt_operacao' => DateTime::createFromFormat('Y-m-d H:i:s', $record['DT_OPERACAO'])->format('Y/m/d'),
                'co_ies' => $record['CO_IES'],
                'no_ies' => $record['NO_IES'],
                'sg_ies' => $record['SG_IES'],
                'sg_uf_ies' => $record['SG_UF_IES'],
                'ensino_medio' => $record['ENSINO_MEDIO'],
                'quilombola' => $record['QUILOMBOLA'],
                'deficiente' => $record['PcD'],
                'st_rank_ensino_medio' => $record['ST_RANK_ENSINO_MEDIO'],
                'st_rank_raca' => $record['ST_RANK_RACA'],
                'st_rank_quilombola' => $record['ST_RANK_QUILOMBOLA'],
                'st_rank_pcd' => $record['ST_RANK_PcD'],
                'st_confirma_lgpd' => $record['ST_CONFIRMA_LGPD'],
                'total_membros_familiar' => $record['TOTAL_MEMBROS_FAMILIAR'],
                'renda_familiar_bruta' => $record['RENDA_FAMILIAR_BRUTA'],
                'salario_minimo' => $record['SALARIO_MINIMO'],
                'dt_curso_inscricao' => $record['DT_CURSO_INSCRICAO'],
                'hr_curso_inscricao' => $record['HR_CURSO_INSCRICAO'],
                'dt_mes_dia_inscricao' => $record['DT_MES_DIA_INSCRICAO'],
                'nu_nota_curso_l' => $record['NU_NOTA_CURSO_L'],
                'nu_nota_curso_ch' => $record['NU_NOTA_CURSO_CH'],
                'nu_nota_curso_cn' => $record['NU_NOTA_CURSO_CN'],
                'nu_nota_curso_m' => $record['NU_NOTA_CURSO_M'],
                'nu_nota_curso_r' => $record['NU_NOTA_CURSO_R'],
                'st_adesao_acao_afirmativa_curs' => $record['ST_ADESAO_ACAO_AFIRMATIVA_CURS'],
                'st_aprovado' => $record['ST_APROVADO'],
                'dt_mes_dia_matricula' => $record['DT_MES_DIA_MATRICULA'],
                'st_matricula_cancelada' => $record['ST_MATRICULA_CANCELADA'],
                'dt_matricula_cancelada' => $record['DT_MATRICULA_CANCELADA'],
                'modalidade_original' => $record['MODALIDADE_ORIGINAL'],
                'modalidade_final' => $record['MODALIDADE_FINAL'],
                'no_acao_afirmativa_propria_ies' => $record['NO_ACAO_AFIRMATIVA_PROPRIA_IES'],
                'perfil_economico_lei_cotas' => $record['PERFIL_ECONOMICO_LEI_COTAS'],
                'tipo_concorrencia' => $record['TIPO_CONCORRENCIA'],
                'chamada_id' => $chamada->id,
                'sisu_id' => $chamada->sisu->id,
                'cota_id' => \App\Models\Cota::getModalidade($record['NO_MODALIDADE_CONCORRENCIA'])->id,
                'candidato_id' => $candidato ? $candidato->id : $nextCandidatoIdValue++,
                'curso_id' => \App\Models\Curso::where('cod_curso', $record['CO_IES_CURSO'])
                    ->where('turno', \App\Models\Curso::TURNO_ENUM[$record['DS_TURNO']])
                    ->first()
                    ->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
    }

    $ordemModalidades = [
        'Ampla concorrência' => 1,
        'que tenham cursado integralmente o ensino médio em qualquer uma das escolas situadas nas microrregiões do Agreste ou do Sertão de Pernambuco.' => 1,
        'AMPLA CONCORRÊNCIA' => 1,
        'Candidatos que, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).' => 2,
        'Candidatos com deficiência, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).' => 3,
        'Candidatos autodeclarados quilombolas, independentemente da renda, tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).' => 4,
        'Candidatos autodeclarados pretos, pardos ou indígenas, independentemente da renda, que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).' => 5,
        'Candidatos com renda familiar bruta per capita igual ou inferior a 1 salário mínimo que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).' => 6,
        'Candidatos com deficiência, que tenham renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012)' => 7,
        'Candidatos autodeclarados quilombolas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).' => 8,
        'Candidatos autodeclarados pretos, pardos ou indígenas, com renda familiar bruta per capita igual ou inferior a 1 salário mínimo e que tenham cursado integralmente o ensino médio em escolas públicas (Lei nº 12.711/2012).' => 9
    ];

    $inscricoesOrdenadas = collect($inscricoesData)
        ->sortBy(function ($item) use ($ordemModalidades) {
            return [
                $ordemModalidades[$item['no_modalidade_concorrencia']] ?? PHP_INT_MAX,
                $item['nu_classificacao'],
            ];
        })->groupBy(['co_ies_curso', 'ds_turno', 'no_modalidade_concorrencia']);

    $candidatosConvocados = [];

    // Os candidatos estão agrupados por curso, turno e modalidade de concorrência. O primeiro foreach itera pelos curso, o segundo pelo turno e o terceiro pela modalidade e o quarto pelos candidatos.
    foreach ($inscricoesOrdenadas as $codCurso => $curso) {
        foreach ($curso as $nomeTurno => $turno) {
            // Processa os candidatos até preencher todas as vagas reais de todas as modalidades
            foreach ($turno as $nomeModalidade => $modalidade) {

                // Acessa a tabela intermediária
                $cotaCurso = \App\Models\Cota::getModalidade($nomeModalidade)
                    ->cursos()
                    ->where('cod_curso', $codCurso)
                    ->where('turno', \App\Models\Curso::TURNO_ENUM[$nomeTurno])
                    ->wherePivot('sisu_id', $chamada->sisu->id)
                    ->first()
                    ->pivot;

                $vagasReais = $cotaCurso->quantidade_vagas - $cotaCurso->vagas_ocupadas;
                $contador = 0;

                foreach ($modalidade as $candidato) {
                    if ($contador < $vagasReais) { // Candidatos que possuem vaga garantida
                        $convocado = false;

                        // Verifica se o candidato já foi convocado
                        foreach ($candidatosConvocados as $index => $candidatoConvocado) {
                            if ($candidato['ds_email'] === $candidatoConvocado['ds_email']) {
                                $convocado = true;
                                break;
                            }
                        }

                        if ($convocado) {
                            continue;
                        } else { // Adiciona o candidato à lista de convocados
                            $candidato['cota_vaga_ocupada_id'] = \App\Models\Cota::getModalidade($nomeModalidade)->id;
                            $candidatosConvocados[] = $candidato;
                            $contador++;
                        }
                    } else break;
                }
            }

            // Processa os candidatos até preencher todas as vagas reserva de todas as modalidades
            foreach ($turno as $nomeModalidade => $modalidade) {

                // Acessa a tabela intermediária
                $cotaCurso = \App\Models\Cota::getModalidade($nomeModalidade)
                    ->cursos()
                    ->where('cod_curso', $codCurso)
                    ->where('turno', \App\Models\Curso::TURNO_ENUM[$nomeTurno])
                    ->wherePivot('sisu_id', $chamada->sisu->id)
                    ->first()
                    ->pivot;

                // Recupera o multiplicador da modalidade
                $multiplicador = \App\Models\MultiplicadorVaga::where([
                    ['cota_curso_id', $cotaCurso->id],
                    ['chamada_id', $chamada->id]
                ])->first();

                // Multiplica a quantidade de vagas reais pelo multiplicador
                $multiplicador = $multiplicador ? $multiplicador->multiplicador : 1;
                $vagasReais = $cotaCurso->quantidade_vagas - $cotaCurso->vagas_ocupadas;
                $vagasMultiplicadas = $vagasReais * $multiplicador;

                $contador = $vagasReais; // Começa a contar a partir do número de vagas reais pois as vagas reais já foram preenchidas

                foreach ($modalidade as $candidato) {
                    if ($contador < $vagasMultiplicadas) { // Candidatos que entrarão na reserva
                        $convocado = false;

                        foreach ($candidatosConvocados as $index => $candidatoConvocado) {
                            if ($candidato['ds_email'] === $candidatoConvocado['ds_email']) {
                                $convocado = true;
                                break;
                            }
                        }

                        if ($convocado) {
                            continue;
                        } else {
                            // Recupera todas as inscrições que possuem o mesmo RG do candidato no curso e turno atual
                            $duplicatas = $turno->flatmap(function ($modalidade) {
                                return $modalidade;
                            })->filter(function ($inscrito) use ($candidato) {
                                return $inscrito['ds_email'] === $candidato['ds_email'];
                            });

                            $dados = []; // Armazena a diferença entre a quantidade de vagas disponíveis e a classificação do candidato, juntamente como candidato que possui o menor valor dessa diferença
                            foreach ($duplicatas as $duplicata) {
                                $pivot = \App\Models\Cota::getModalidade($duplicata['no_modalidade_concorrencia'])
                                    ->cursos()
                                    ->where('cod_curso', $codCurso)
                                    ->where('turno', \App\Models\Curso::TURNO_ENUM[$nomeTurno])
                                    ->wherePivot('sisu_id', $chamada->sisu->id)
                                    ->first()
                                    ->pivot;
                                
                                $vagas = $pivot->quantidade_vagas - $pivot->vagas_ocupadas;
                                $diferenca = $vagas - $duplicata['nu_classificacao'];

                                if (empty($dados) || $diferenca > $dados[0]) {
                                    $dados[0] = $diferenca;
                                    $dados[1] = $duplicata;
                                }
                            }

                            if ($dados[1]['no_modalidade_concorrencia'] === $nomeModalidade) { // Se a duplicata com mais chances de ser aprovada for o candidato atual, então adicione a lista de convocados
                                $candidato['cota_vaga_ocupada_id'] = \App\Models\Cota::getModalidade($nomeModalidade)->id;
                                $candidatosConvocados[] = $candidato;
                                $contador++;
                            }
                        }
                    } else break;
                }
            }
        }
    }
    dd($candidatosConvocados);

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


        //E recuperamos a instancia do curso do banco de dados

        /*Para a nova regra de chamadas da lista de espera, e necessario preencher o restante de vagas da ampla concorrencia
        com os candidatos com as maiores notas  daquele curso*/

        $candidatosCurso = collect();
        foreach ($cursos[$indexCurso] as $modalidadeAtual) {
            $candidatosCurso = $candidatosCurso->concat($modalidadeAtual->all());
        }

        $candidatosCurso = $candidatosCurso->sortByDesc(function ($candidato) {
            return $candidato['nu_nota_candidato'];
        });

        $cotaAC = \App\Models\Cota::firstWhere('cod_cota', 'A0');
        $cota_cursoA0 = $curs->cotas()->where('cota_id', $cotaAC->id)->where('sisu_id', $chamada->sisu->id)->first()->pivot;
        $vagasCotaA0 = $cota_cursoA0->quantidade_vagas - $cota_cursoA0->vagas_ocupadas;

        //chamamos o número de vagas disponíveis vezes o valor do multiplicador passado
        $multiplicador = \App\Models\MultiplicadorVaga::where('cota_curso_id', $cota_cursoA0->id)->first();
        if ($multiplicador != null) {
            $vagasCotaA0 *= $multiplicador->multiplicador;
        }

        //$candidatosCurso = $candidatosCurso->slice(0, $vagasCotaA0);

        $vagasCota = $this->fazerCadastro($cotaAC, null, $curs, $candidatosCurso, $vagasCotaA0);

        $vagasCotaCollection = collect();
        $vagasCotaCollection->push(0);

        //Varremos todas as cotas do curso
        foreach ($curs->cotas()->where('sisu_id', $chamada->sisu->id)->get() as $cota) {
            if ($cota->cod_cota != $cotaAC->cod_cota) {
                //recuperamos informações da quantidade que iremos chamar
                $cota_curso = $curs->cotas()->where('cota_id', $cota->id)->where('sisu_id', $chamada->sisu->id)->first()->pivot;

                $vagasCota = $cota_curso->quantidade_vagas - $cota_curso->vagas_ocupadas;
                //chamamos o número de vagas disponíveis vezes o valor do multiplicador passado
                $multiplicador = \App\Models\MultiplicadorVaga::where([['cota_curso_id', $cota_curso->id], ['chamada_id', $chamada->id]])->first();
                if (!is_null($multiplicador)) {
                    $vagasCota *= $multiplicador->multiplicador;
                }

                //aqui veremos se essa cota tem candidatos inscritos para fazer o cadastro
                $cursoAtual = $cotasCursosCOD[$indexCurso];
                $modalidadeDaCotaIndex = null;

                //Se o curso atual possuir algum candidato da modalidade descrita na descricao da cota, significa que temos quem chamar
                foreach ($cursoAtual as $index => $modalidadeCursoAtual) {
                    if ($modalidadeCursoAtual == $cota->descricao) {
                        $modalidadeDaCotaIndex = $index;
                        break;
                    }
                }
                //Então assim faremos
                if (!is_null($modalidadeDaCotaIndex)) {
                    $vagasCota = $this->fazerCadastro($cota, $cota, $curs, $cursos[$indexCurso][$modalidadeDaCotaIndex], $vagasCota);
                }
                $vagasCotaCollection->push($vagasCota);
            }
        }
        //Varremos todas as cotas do curso
        foreach ($curs->cotas()->where('sisu_id', $chamada->sisu->id)->get() as $indice => $cota) {
            if ($cota->cod_cota != $cotaAC->cod_cota) {
                $vagasCota = $vagasCotaCollection[$indice];
                //Caso restem vagas, faremos o remanejamento
                if ($vagasCota > 0) {
                    foreach ($cota->remanejamentos as $remanejamento) {
                        $cotaRemanejamento = $remanejamento->proximaCota;
                        $cursoAtual = $cotasCursosCOD[$indexCurso];

                        $modalidadeDaCotaIndex = null;

                        foreach ($cursoAtual as $indexRemanejamento => $modalidadeCursoAtualRemanejamento) {
                            if ($modalidadeCursoAtualRemanejamento == $cotaRemanejamento->descricao) {
                                $modalidadeDaCotaIndex = $indexRemanejamento;
                                break;
                            }
                        }
                        if (!is_null($modalidadeDaCotaIndex)) {
                            $vagasCota = $this->fazerCadastro($cota, $cotaRemanejamento, $curs, $cursos[$indexCurso][$modalidadeDaCotaIndex], $vagasCota);
                        }
                        if ($vagasCota == 0) {
                            break;
                        }
                    }
                }
            }
        }
});
