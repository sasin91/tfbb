<?php

namespace App\Listeners\User;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLastSeenAt
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Authenticated  $event
     * @return void
     */
    public function handle($event)
    {
        if (! optional($event->user->last_seen_at)->isToday()) {
            $event->user->update(['last_seen_at' => now()]);
        }
    }
}
