<?php

namespace App\Mail;

use App\Usuario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetarSenha extends Mailable
{
    use Queueable, SerializesModels;

    private $usuario;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject(config("app.name") . ' - Recuperação de Senha');
        $this->to($this->usuario->email,$this->usuario->nome);
        return $this->view('mail.resetarSenha',[
            'usuario'=>$this->usuario
        ]);
    }
}
