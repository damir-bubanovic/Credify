<?php

namespace App\Notifications\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreditsDepletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $balance
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $tenantName = method_exists($notifiable, 'getAttribute')
            ? ($notifiable->name ?? $notifiable->id ?? 'your tenant')
            : 'your tenant';

        return (new MailMessage)
            ->subject("Credits depleted for {$tenantName}")
            ->line("Your current credit balance is {$this->balance}.")
            ->line('Credit-based features are now disabled because your credits are fully depleted.')
            ->line('Please top up your credits or upgrade your subscription plan to resume normal usage.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'balance' => $this->balance,
        ];
    }
}
