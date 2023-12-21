<?php

namespace App\Mail;

use App\Models\Inscricao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeclaracaoPreMatricula extends Mailable
{
    use Queueable, SerializesModels;

    private $inscricao;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Inscricao $inscricao)
    {
        $this->inscricao = $inscricao;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.declaracaoPreMatricula')->with(['inscricao' => $this->inscricao]);
    }
}
