<?php

namespace App\Notifications;

use App\Http\Controllers\InscricaoController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inscricao;

class ComprovanteEnvioDocumentosNotification extends Notification
{
    use Queueable;
    public $assunto;
    public $inscricao;
    public $arquivos;
    public $inscricaoController;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($assunto, Inscricao $inscricao)
    {
        $this->assunto = $assunto;
        $this->inscricao = $inscricao;
        $this->arquivos = $inscricao->arquivos;
        $this->inscricaoController = new InscricaoController();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown(
            'mail.comprovante', 
            [
                'inscricao' => $this->inscricao,
                'arquivos' => $this->arquivos,
                'protocolo' => $this->inscricao->gerarProtocolo(),
                'documentos_requisitados' => $this->inscricaoController->documentosRequisitados($this->inscricao->id),
            ]
        )->subject($this->assunto);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
