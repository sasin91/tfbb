<?php

namespace App\Providers;

use App\Http\Resources\DietResource;
use App\Http\Resources\MealResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\RecordingResource;
use App\Http\Resources\WorkoutResource;
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
        $this->registerValidationExtensions();

        $this->dontWrapResources();
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

    public function dontWrapResources()
    {
        DietResource::withoutWrapping();
        MealResource::withoutWrapping();
        OfferResource::withoutWrapping();
        RecordingResource::withoutWrapping();
        WorkoutResource::withoutWrapping();
    }

    public function registerValidationExtensions()
    {
        Validator::extend('spamfree', SpamFree::class.'@passes');
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
