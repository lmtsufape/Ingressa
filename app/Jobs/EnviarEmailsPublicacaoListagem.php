<?php

namespace App\Jobs;

use App\Models\Listagem;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailDivulgacaoListagemNotification;
use App\Models\User;

class EnviarEmailsPublicacaoListagem implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $listagem;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Listagem $listagem)
    {
        $this->listagem = $listagem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inscricoes = $this->listagem->chamada->inscricoes;

        foreach ($inscricoes as $inscricao) {
            $user = $inscricao->candidato->user;

            if ($user->email != null) {
                Notification::send($user, new EmailDivulgacaoListagemNotification($this->listagem, $user));
            } else {
                $user_inscrito = User::gerar_user_inscricao($inscricao);
                Notification::send($user_inscrito, new EmailDivulgacaoListagemNotification($this->listagem, $user));
            }
        }
    }
}
