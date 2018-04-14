<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Laravel\Spark\Notifications\SparkChannel;

class YouWereMentioned extends Notification
{
    use Queueable;

    /**
     * The model the user was mentioned in.
     *     
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

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
     * @return \Laravel\Spark\Notifications\SparkNotification
     */
    public function toSpark($notifiable)
    {
        return (new SparkNotification)
            ->icon('fa fa-comment')
            ->body('You were mentioned!')
            ->action('Go check it.', $this->model->link);
    }
}
