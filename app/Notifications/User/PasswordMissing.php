<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravel\Spark\Notifications\SparkChannel;
use Laravel\Spark\Notifications\SparkNotification;

class PasswordMissing extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SparkChannel::class];
    }

    /**
     * Get the spark representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->action('change password', url('/home#change-password'))
            ->icon('fa fa-exclamation-circle')
            ->body('Your account currently has no password, it is recommended that you define one.');
    }
}
