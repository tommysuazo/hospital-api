<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AuthVerificationCodeNotification extends Notification
{
    public function __construct(
        public readonly string $code,
        public readonly string $purpose,
        public readonly int $expiresInMinutes = 30,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = match ($this->purpose) {
            'registration' => 'Confirma tu cuenta',
            'password_reset' => 'Código para restablecer tu contraseña',
            default => 'Código de inicio de sesión',
        };

        return (new MailMessage)
            ->subject($subject.' | Centro Médico Oriental')
            ->greeting('Hola, '.$notifiable->first_name)
            ->line('Utiliza este código de seguridad para continuar:')
            ->line('**'.$this->code.'**')
            ->line("El código vence en {$this->expiresInMinutes} minutos y solo puede utilizarse una vez.")
            ->line('Si no realizaste esta solicitud, puedes ignorar este correo.');
    }
}
