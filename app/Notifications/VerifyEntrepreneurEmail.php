<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEntrepreneurEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public ?string $url = null;

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Activá tu cuenta de CB Tiendas')
            ->view('mail.verifyEntrepreneurEmail', [
                'user' => $notifiable,
                'verificationUrl' => $this->verificationUrl($notifiable),
                'expirationMinutes' => config('auth.verification.expire', 60),
            ]);
    }

    protected function verificationUrl($notifiable): string
    {
        return $this->url ?? parent::verificationUrl($notifiable);
    }
}
