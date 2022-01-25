<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\DataChamada;
use App\Models\User;

class EmailDataInicioNotification extends Notification
{
    use Queueable;
    public $data;
    public $user;
    public $assunto;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(DataChamada $data, User $user, $assunto)
    {
        $this->data = $data;
        $this->user = $user;
        $this->assunto = $assunto;
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
            'mail.data_inicio', 
            [
                'user' => $this->user,
                'data' => $this->data,
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
