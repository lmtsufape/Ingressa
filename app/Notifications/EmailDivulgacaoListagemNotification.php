<?php

namespace App\Notifications;

use App\Models\Listagem;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailDivulgacaoListagemNotification extends Notification
{
    use Queueable;
    public $listagem;
    public $user;
    public $assunto;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Listagem $listagem, User $user)
    {
        $this->listagem = $listagem;
        $this->user = $user;
        $this->assunto = 'Notificação de digulgação de listagem';
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
            'mail.divulgacao',
            [
                'listagem' => $this->listagem,
                'user' => $this->user,
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
