<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdminPasswordNotification extends Notification
{
    use Queueable;

    protected string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nova conta de administrador criada')
            ->line('Sua conta de administrador foi criada.')
            ->line('Use a senha temporária abaixo para acessar o sistema:')
            ->line($this->password)
            ->line('Por segurança, será solicitado que você altere a senha ao realizar o primeiro login.');
    }
}
