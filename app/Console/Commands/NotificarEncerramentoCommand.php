<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataChamada;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailDataEncerramentoNotification;

class NotificarEncerramentoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:encerramento';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia e-mails para todos os respectivos candidatos quando a respectiva data está em seu último dia.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datas = $this->getDataEncerramento();
        if($datas->count() > 0) {
            foreach ($datas as $data) {
                $this->enviarEmails($data);
            }
        }
    }

    /**
     * Retorna as datas que tem encerramento hoje.
     * 
     * @return collect $datas
     */
    private function getDataEncerramento() 
    {
        $datas = DataChamada::where('data_fim', now()->format('Y-m-d'))->get();
        return $datas;
    }

    /**
     * Envia os emails para todos os candidatos da chamada a qual a data corresponde.
     * 
     * @param DataChamada $data
     * @return void
     */
    private function enviarEmails(DataChamada $data)
    {
        if ($data->ehDataDeEnvio()) {
            $inscricoes = $data->chamada->inscricoes;
            foreach ($inscricoes as $inscricao) {
                $user = $inscricao->candidato->user;

                if ($user->email != null) {
                    Notification::send($user, new EmailDataEncerramentoNotification($data, $user, 'Notificação automática ' . env('APP_NAME')));
                } else {
                    $user_inscricao = User::gerar_user_inscricao($inscricao);
                    Notification::send($user_inscricao, new EmailDataEncerramentoNotification($data, $user_inscricao, 'Notificação automática ' . env('APP_NAME')));
                }
            }
        }
    }
}
