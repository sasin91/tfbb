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

        $this->bindGuzzleCache();

        $this->registerRemoteAPIServices();
    }

    public function overrideSparkBindings()
    {
        $this->app->bind(UserRepositoryContract::class, ExtendedUserRepository::class);
    }

    public function bindGuzzleCache()
    {
        $this->app->bind('Guzzle cache', function ($app) {
            $strategy = new PrivateCacheStrategy(
                new LaravelCacheStorage($app['cache']->store('redis'))
            );

            return new CacheMiddleware(
                $strategy
            );
        });
    }

    public function registerRemoteAPIServices()
    {
        $this->bindRemoteAPIManager();
        $this->bindRemoteAPIShortcuts();
    }

    public function bindRemoteAPIManager()
    {
        $this->app->singleton(RemoteAPIManager::class, function ($app) {
            return new RemoteAPIManager($app);
        });
    }

    /**
     * Register some shortcuts to remote api services, by binding them into the container.
     * This allows developers to resolve RemoteAPI services directly by dependency injection,
     * and/or Real-time facades.
     * 
     * @example SomeClass@__construct(\NDB $ndb)
     * @example \Facades\NDB::food($id) 
     * 
     * @return void
     */
    public function bindRemoteAPIShortcuts()
    {
        $this->app->bind('NDB', function ($app) {
            return $app->make(RemoteAPIManager::class)->driver('NDB');
        });

        $this->app->bind('Wger', function ($app) {
            return $app->make(RemoteAPIManager::class)->driver('Wger');
        });
    }
}
