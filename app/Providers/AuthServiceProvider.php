<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Board' => 'App\Policies\BoardPolicy',
        'App\Thread' => 'App\policies\ThreadPolicy',
        'App\Reply' => 'App\Policies\ReplyPolicy',
        'App\Workout' => 'App\Policies\WorkoutPolicy',
        'App\Profile' => 'App\Policies\ProfilePolicy',
        'App\Recording' => 'App\Policies\RecordingPolicy',
        'App\Offer' => 'App\Policies\OfferPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
