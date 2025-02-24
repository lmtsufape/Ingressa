<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCandidatoRequest;
use App\Models\Candidato;
use App\Models\User;
use App\Models\Inscricao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailCandidatoNotification;
use Illuminate\Support\Facades\Storage;

class CandidatoController extends Controller
{

    public static function verificacao(Request $request)
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        $dt_nasc = $request->dt_nasc;
        $candidato = Candidato::where('nu_cpf_inscrito', $cpf)
            ->where('dt_nascimento', $dt_nasc)
            ->first();

        if ($candidato == null) {
            return redirect(route('primeiro.acesso'))
                ->withErrors(['cpf' => 'Dados incorretos.'])
                ->withInput();
        } else {

            $user = User::find($candidato->user_id);

            if ($user->primeiro_acesso == true) {

                return view('candidato.acesso_edit', compact('user'));
            } else {
                return redirect(route('primeiro.acesso'))
                    ->withErrors(['cpf' => 'Primeiro acesso já realizado!'])
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
            Notification::send($user, new EmailCandidatoNotification($request->assunto, $request->input('conteúdo'), $inscricao));
        } else {
            $user_inscricao = User::gerar_user_inscricao($inscricao);
            Notification::send($user_inscricao, new EmailCandidatoNotification($request->assunto, $request->input('conteúdo'), $inscricao));
        }

        return redirect(route('inscricao.show.analisar.documentos', ['sisu_id' => $inscricao->chamada->sisu->id, 'chamada_id' => $inscricao->chamada->id, 'curso_id' => $inscricao->curso->id, 'inscricao_id' => $inscricao->id]))->with(['success' => 'E-mail enviado com sucesso!']);
    }

    public function update(Candidato $candidato, Inscricao $inscricao, UpdateCandidatoRequest $request)
    {
        $validated = $request->validated();

        if ($request->user->role == User::ROLE_ENUM['candidato']) {
            if ($request->hasFile('requerimento_nome_social')) {
                $caminho = $request->file('requerimento_nome_social')->storeAs("documentos/inscricaos/$inscricao->id", 'requerimento_nome_social.pdf');

                $inscricao->arquivos()->updateOrCreate([
                    'nome' => 'requerimento_nome_social',
                ], [
                    'nome' => 'requerimento_nome_social',
                    'caminho' => $caminho,
                ]);
            } else {
                $arquivo = $inscricao->arquivos()->where('nome', 'requerimento_nome_social')->first();

                if ($arquivo) {
                    Storage::delete($arquivo->caminho);
                    $arquivo->delete();
                }
            }
        }

        $validated['necessidades'] = implode(',', $validated['necessidades']);
        $candidato->fill($validated);
        $candidato->atualizar_dados = false;
        $inscricao->fill($validated);

        $candidato->update();
        $inscricao->update();

        if (auth()->user()->role == User::ROLE_ENUM['admin']) {
            return redirect()->back()->with(['success' => "Dados atualizados!"]);
        } else {
            return redirect(route('inscricaos.index'))->with(['success' => 'Dados atualizados!']);
        }
    }

    public function edit(Candidato $candidato, Inscricao $inscricao)
    {
        $this->authorize('canAtualizarFicha', $inscricao);
        $cores_racas = array_keys(Candidato::ETNIA_E_COR);
        return view('candidato.atualizar_dados', compact('candidato', 'inscricao', 'cores_racas'));
    }
}
