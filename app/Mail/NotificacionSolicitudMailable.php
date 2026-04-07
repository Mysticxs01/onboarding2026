<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\ProcesoIngreso;
use App\Models\Solicitud;

class NotificacionSolicitudMailable extends Mailable
{
    use Queueable, SerializesModels;

    public ProcesoIngreso $proceso;
    public array $solicitudes;
    public string $nombreArea;
    public string $urlPanel;

    public function __construct(ProcesoIngreso $proceso, array $solicitudes, string $nombreArea, string $urlPanel)
    {
        $this->proceso = $proceso;
        $this->solicitudes = $solicitudes;
        $this->nombreArea = $nombreArea;
        $this->urlPanel = $urlPanel;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Nuevo Proceso de Ingreso - {$this->proceso->codigo}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.notificacion-solicitud',
            with: [
                'proceso' => $this->proceso,
                'solicitudes' => $this->solicitudes,
                'nombreArea' => $this->nombreArea,
                'urlPanel' => $this->urlPanel,
                'fechaActual' => now()->format('d/m/Y H:i'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
