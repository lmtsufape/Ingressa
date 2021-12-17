<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\User;
use App\Models\Inscricao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailCandidatoNotification;

class CandidatoController extends Controller
{

    public static function verificacao(Request $request)
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        $dt_nasc = $request->dt_nasc;
        $candidato = Candidato::where([
            ['nu_cpf_inscrito', '=', $cpf],
            ['dt_nascimento', '=', $dt_nasc]]
        )->first();

        if ($candidato == null){
            return redirect(route('primeiro.acesso'))
                ->withErrors('Dados Incorretos')
                ->withInput();
        }
        else{
            
            $user = User::where('id','=',$candidato->user_id)->first();
            
            if ($user->primeiro_acesso == true){
                
                return view('candidato.acesso_edit', compact('user'));
            }
            else{
                return redirect(route('primeiro.acesso'))
                    ->withErrors('Login já cadastrado')
                    ->withInput();
            }

        }

    }

    public static function prepararAdicionar()
    {
        return view('candidato.verificacao');
    }

    public static function editarAcesso(User $user)
    {
        return view('candidato.acesso_edit', ['user' => $user]);
    }

    public function enviarEmail(Request $request) 
    {
        $inscricao = Inscricao::find($request->inscricao_id);

        $request->validate([
            'assunto' => 'nullable',
            'conteúdo' => 'required|max:5000',
        ]);

        $user = $inscricao->candidato->user;
        if ($user->email != null) {
            Notification::send($user, new EmailCandidatoNotification($request->assunto, $request->input('conteúdo')));
        }

        $user_inscricao = User::gerar_user_inscricao($inscricao);
        Notification::send($user_inscricao, new EmailCandidatoNotification($request->assunto, $request->input('conteúdo')));

        return redirect(route('inscricao.show.analisar.documentos', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id, 'inscricao_id' => $inscricao->candidato->id]))->with(['success' => 'E-mail enviado com sucesso!']);
    }

}
