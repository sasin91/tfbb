<?php

namespace App\Listeners\User;

use App\Events\User\UserUpdated;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ScheduleAgeRefresh
{   
    /**
     * Laravel schedule
     * 
     * @var \Illuminate\Console\Scheduling\Schedule
     */
    protected $schedule;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Handle the event.
     *
     * @param  UserUpdated $event
     * @return void
     */
    public function handle($event)
    {
        $id = $event->user->getKey();

        $this->schedule->call(function () use ($id) {
            $user = User::find($id);

            if ($user) {
                $user->refreshAgeIfNeeded();
            }
        })->cron(
            $this->makeCronExpression($event->user->born_at)
        );

    }

    protected function makeCronExpression($date)
    {
        $minute = $date->format('i');
        $hour = $date->format('H');
        $day = $date->format('d') ?: '*';
        $month = $date->format('m') ?: '*';
        $weekday = $date->format('w') ?: '*';

        return "{$minute} {$hour} {$day} {$month} {$weekday}";
    }
}
