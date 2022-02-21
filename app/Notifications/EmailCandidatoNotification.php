<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inscricao;
use App\Models\User;

class EmailCandidatoNotification extends Notification
{
    use Queueable;

    public $assunto = null;
    public $conteudo = null;
    public $inscricao;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($assunto, $conteudo, $inscricao)
    {
        $this->assunto = $assunto;
        $this->conteudo = $conteudo;
        $this->inscricao = $inscricao;
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
            'mail.candidato',
            [
                'inscricao' => $this->inscricao,
                'conteudo' => $this->conteudo,
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
