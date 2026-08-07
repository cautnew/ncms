<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\WebsiteUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WebsiteInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly WebsiteUser $membership,
        private readonly User $inviter,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $website = $this->membership->website;
        $frontendUrl = rtrim((string) config('app.frontend_url'), '/');

        return (new MailMessage)
            ->subject("You've been invited to {$website->name}")
            ->greeting("Hello {$notifiable->name},")
            ->line("{$this->inviter->name} invited you to join \"{$website->name}\" as {$this->membership->role->label()}.")
            ->action('Go to '.$website->name, $frontendUrl)
            ->line('If you do not have a password yet, use the "Forgot password" option to set one before logging in.');
    }
}
