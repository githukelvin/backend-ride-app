<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\AfricasTalking\AfricasTalkingChannel;
use NotificationChannels\AfricasTalking\AfricasTalkingMessage;

class LoginNeedsVerification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return [AfricasTalkingChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toAfricasTalking($notifiable): AfricasTalkingMessage
    {
        $loginCode = rand(111111, 999999);
        $notifiable->update([
            'login_code' => $loginCode
        ]);
        return (new AfricasTalkingMessage())
            ->content("Your ride login code is $loginCode,don't share with anyone");

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed
     */
    public function toArray(): array
    {
        return [
            //
        ];
    }
}
