<?php

namespace App\Providers;

use App\Rules\SpamFree;
use App\Services\RemoteAPIManager;
use App\Services\Wger;
use App\Spark\ExtendedUserRepository;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use Kevinrob\GuzzleCache\Strategy\CacheStrategyInterface;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Laravel\Horizon\Horizon;
use Laravel\Spark\Contracts\Repositories\UserRepository as UserRepositoryContract;
use Laravel\Spark\Spark;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('spamfree', SpamFree::class.'@passes');

        Horizon::auth(function ($request) {
            if (is_null($request->user())) {
                return false;
            }

            return Spark::developer($request->user()->email);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->overrideSparkBindings();

        $this->app->bind('Guzzle cache', function ($app) {
            $strategy = new PrivateCacheStrategy(
                new LaravelCacheStorage($app['cache']->store('redis'))
            );

            return new CacheMiddleware(
                $strategy
            );
        });

        $this->app->singleton(RemoteAPIManager::class, function ($app) {
            return new RemoteAPIManager($app);
        });
    }

    public function overrideSparkBindings()
    {
        $this->app->bind(UserRepositoryContract::class, ExtendedUserRepository::class);
    }
}
