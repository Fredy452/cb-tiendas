<?php

namespace App\Mail;

use App\Models\Store;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StoreCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Store $store,
        public string $status = 'pending',
        public ?string $publicUrl = null,
        public bool $changesSubmitted = false,
    )
    {
        $this->status = in_array($status, ['pending', 'approved', 'rejected', 'inactive'], true)
            ? $status
            : 'pending';

        $this->publicUrl = $this->status === 'approved'
            ? route('tiendas.show', $store->slug ?: $store->getKey())
            : null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->statusSubjectLine(),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.storeStatusChanged',
            with: [
                'status' => $this->status,
                'publicUrl' => $this->publicUrl,
                'changesSubmitted' => $this->changesSubmitted,
            ],
        );
    }

    private function statusSubjectLine(): string
    {
        if ($this->changesSubmitted) {
            return "Cambios en revisión: {$this->store->name}";
        }

        return match ($this->status) {
            'approved' => "Tienda aprobada: {$this->store->name}",
            'rejected' => "Tienda rechazada: {$this->store->name}",
            'inactive' => "Tienda inactivada: {$this->store->name}",
            default => "Tienda registrada: {$this->store->name}",
        };
    }

    /**
     * Get the attachments for the message.
     *
    * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
