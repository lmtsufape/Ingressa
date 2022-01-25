<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataChamada;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailDataInicioNotification;
use App\Models\User;

class NotificarInicioCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enviar:inicio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia e-mails para todos os respectivos candidatos quando a respectiva data é aberta.';

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
        $datas = $this->getDataInicio();
        if($datas->count() > 0) {
            foreach ($datas as $data) {
                $this->enviarEmails($data);
            }
        }
    }

    /**
     * Retorna datas de inicios se forem hoje.
     *
     * @return collect DataChamada $dataChamada
     */
    private function getDataInicio()
    {
        $datas = DataChamada::where('data_inicio', now()->format('Y-m-d'))->get();
        
        return $datas;
    }

    /**
     * Envia os emails para cada candidato vinculado a chamada daquela data.
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
                    Notification::send($user, new EmailDataInicioNotification($data, $user, 'Notificação automática ' . env('APP_NAME')));
                } else {
                    $user_inscricao = User::gerar_user_inscricao($inscricao);
                    Notification::send($user_inscricao, new EmailDataInicioNotification($data, $user_inscricao, 'Notificação automática ' . env('APP_NAME')));
                }
            }
        }
    }
}
