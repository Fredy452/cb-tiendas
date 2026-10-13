<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetEntrepreneurPassword extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Restablecé tu contraseña de CB Tiendas')
            ->greeting('Hola '.$notifiable->name.',')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
            ->action('Elegir nueva contraseña', $this->resetUrl($notifiable))
            ->line('Este enlace vence en '.config('auth.passwords.'.config('auth.defaults.passwords').'.expire').' minutos.')
            ->line('Si no solicitaste este cambio, podés ignorar este correo.');
    }
}
