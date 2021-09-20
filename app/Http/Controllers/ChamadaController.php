<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChamadaRequest;
use App\Models\Candidato;
use App\Models\Chamada;
use App\Models\Inscricao;
use App\Models\Sisu;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
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
    public function show(Chamada $chamada)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Chamada  $chamada
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
        $chamada = Chamada::find($id);
        $sisu = Sisu::find($chamada->sisu_id);
        $chamada->delete();

        return redirect(route('sisus.show', ['sisu' => $sisu->id]))->with(['success' => 'Chamada deletada com sucesso!']);
    }

    public function importarCandidatos(Request $request, $sisu_id, $chamada_id)
    {
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
            $data = fopen($arquivo,'r');
            $this->cadastrarCandidatosRegular($data, $chamada);
        }
        $chamada->update();return redirect(route('sisus.show', ['sisu' => $chamada->sisu->id]))->with(['success' => 'Candidatos importados com sucesso!']);
    }

    public function cadastrarCandidatosRegular($dados, $chamada)
    {
        $primeira = true;
        ini_set('max_execution_time', 300);
        while ( ($data = fgetcsv($dados,";",';') ) !== FALSE ) {
            if($primeira){
                $primeira = false;
            }else{
                $inscricao = new Inscricao([
                    'status' => Inscricao::STATUS_ENUM['em_chamada'],
                    'protocolo' => Hash::make($data[8].$chamada->id),
                    'nu_etapa' => $data[0],
                    'no_campus' => $data[1],
                    'co_ies_curso' => $data[2],
                    'no_curso' => $data[3],
                    'ds_turno' => $data[4],
                    'ds_formacao' => $data[5],
                    'qt_vagas_concorrencia' => $data[6],
                    'co_inscricao_enem' => $data[7],

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
                ]);

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
}
