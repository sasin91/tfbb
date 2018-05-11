<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\Horizon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Board' => 'App\Policies\BoardPolicy',
        'App\Diet' => 'App\Policies\DietPolicy',
        'App\Thread' => 'App\Policies\ThreadPolicy',
        'App\Reply' => 'App\Policies\ReplyPolicy',
        'App\Workout' => 'App\Policies\WorkoutPolicy',
        'App\Profile' => 'App\Policies\ProfilePolicy',
        'App\Recording' => 'App\Policies\RecordingPolicy',
        'App\Offer' => 'App\Policies\OfferPolicy',
        'App\Meal' => 'App\Policies\MealPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->configureHorizon();
    }

    public function configureHorizon()
    {
        Horizon::auth(function ($request) {
            if (is_null($request->user())) {
                return false;
            }

            return Spark::developer($request->user()->email);
        });
    }
}
