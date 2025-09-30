<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Mailable genérico para enviar cualquier tipo de notificación
 *
 * Uso:
 * Mail::to($email)->send(new NotificacionGeneral(
 *     asunto: 'Nueva Cita Agendada',
 *     titulo: '¡Tienes una nueva cita!',
 *     mensaje: 'Un cliente ha agendado una cita en tu negocio.',
 *     detalles: [
 *         'Cliente' => 'Juan Pérez',
 *         'Fecha' => '15/01/2025',
 *         'Hora' => '10:00 - 11:00',
 *         'Servicio' => 'Corte de cabello'
 *     ],
 *     accionTexto: 'Ver Cita',
 *     accionUrl: route('empresa.configuracion.citas', 1)
 * ));
 */
class NotificacionGeneral extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;
    public $titulo;
    public $mensaje;
    public $detalles;
    public $accionTexto;
    public $accionUrl;
    public $tipoIcono;
    public $colorIcono;

    /**
     * Crear nueva instancia del mailable
     *
     * @param string $asunto Asunto del email
     * @param string $titulo Título principal del email
     * @param string $mensaje Mensaje descriptivo
     * @param array $detalles Array asociativo con información adicional ['Label' => 'Valor']
     * @param string|null $accionTexto Texto del botón de acción (opcional)
     * @param string|null $accionUrl URL del botón de acción (opcional)
     * @param string $tipoIcono success|info|warning|error (default: info)
     * @param string|null $colorIcono Color hexadecimal del ícono (opcional)
     */
    public function __construct(
        string $asunto,
        string $titulo,
        string $mensaje,
        array $detalles = [],
        ?string $accionTexto = null,
        ?string $accionUrl = null,
        string $tipoIcono = 'info',
        ?string $colorIcono = null
    ) {
        $this->asunto = $asunto;
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->detalles = $detalles;
        $this->accionTexto = $accionTexto;
        $this->accionUrl = $accionUrl;
        $this->tipoIcono = $tipoIcono;
        $this->colorIcono = $colorIcono ?? $this->getColorPorTipo($tipoIcono);
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->asunto)
                    ->view('emails.notificacion-general');
    }

    /**
     * Obtener color según el tipo de notificación
     */
    private function getColorPorTipo(string $tipo): string
    {
        return match($tipo) {
            'success' => '#10b981',
            'warning' => '#f59e0b',
            'error' => '#ef4444',
            'info' => '#6366f1',
            default => '#6274c9',
        };
    }
}