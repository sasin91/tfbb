<?php

namespace App\Listeners\User;

use App\Notifications\User\PasswordMissing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyPasswordMissing
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Authenticated  $event
     * @return void
     */
    public function handle($event)
    {
        if (blank($event->user->password)) {
            $event->user->notify(new PasswordMissing);
        }
    }
}
