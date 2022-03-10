<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    private UrlGenerator $urlGenerator;
    private string $token;

    public function __construct(UrlGenerator $urlGenerator, string $token)
    {
        $this->urlGenerator = $urlGenerator;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     */
    public function via($notifiable): array
    {
        return [
            'mail',
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())->view(
            'emails.auth.reset-password',
            [
                'resetUrl' => $this->generatePasswordResetUrl($notifiable->getEmailForPasswordReset()),
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
        ];
    }

    private function generatePasswordResetUrl(string $email): string
    {
        return $this->urlGenerator->route('auth.password.reset', [
            'token' => $this->getToken(),
            'email' => $email,
        ]);
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
