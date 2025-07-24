<?php

// app/Mail/PedidoConfirmado.php

namespace App\Mail;

use App\Models\Checkout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmado extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    public function __construct(Checkout $pedido)
    {
        $this->pedido = $pedido;
    }

    public function build()
    {
        return $this->subject('Confirmación de tu pedido')
                    ->view('emails.pedido-confirmado');
    }
}
