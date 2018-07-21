<?php

namespace Tests;

use App\Exceptions\Handler;
use App\Team;
use App\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Spark\Contracts\Interactions\Settings\Teams\AddTeamMember;
use Laravel\Spark\Spark;
use Tests\RecordsHttpCalls;
use Tests\WithoutBroadcasting;
use Tests\WithoutQueue;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function asDeveloper($user)
    {
        return $this->asDev($user);
    }

    public function asDev($user = null)
    {
        $emailKey = array_rand(Spark::$developers);
        $email = Spark::$developers[$emailKey];

        if (is_null($user)) {
            $user = User::where('email', $email)->firstOr(function () {
                return factory(User::class)->create(['email' => $email]);
            });
        } else {
            $user->update(['email' => $email]);
        }

        return $this->asUser($user);
    }

    public function asModerator($user = null)
    {
        $user = $user ?? factory(User::class)->states('moderator')->create();

        if (! $user->is_moderator) {
            $user->update(['is_moderator' => true]);
        }

        return $this->asUser($user);
    }

    public function asSubscriber($user = null, $subscription = null, $plan = null)
    {
        $user = $user ?? factory(User::class)->create();

        $this->withSubscription($user, $subscription, $plan);

        return $this->asUser($user); 
    }

    public function asUnsubscribed($user = null)
    {
        $user = $user ?? factory(User::class)->create();

        $this->subscriptions->each->delete();

        $this->asUser($user);
    }

    public function asUser($user = null)
    {
        $user = $user ?? factory(User::class)->create();

        $this->be($user);

        $this->assertAuthenticatedAs($user);

        return $this;
    }

    public function asGuest()
    {
        $this->assertGuest();

        return $this;
    }

    public function withSubscription($user, $subscription = null, $plan = null)
    {
        $subscription = $subscription ?? 'default';
        $plan = $plan ?? 'testing';

        if (! $user->subscribed($subscription, $plan)) {
            $subscriptionModel = $user->subscriptions()->create([
                'name' => $subscription,
                'stripe_id' => 'fake-stripe-id',
                'stripe_plan' => $plan,
                'quantity' => 1
            ]); 

            $user->subscriptions->push($subscriptionModel);  
        }

        return $user;
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[WithoutSearchIndexing::class])) {
            $this->disableSearchIndexingForAllTests();
        }

        if (isset($uses[WithoutQueue::class])) {
            $this->disableQueueForAllTests();
        }

        if (isset($uses[WithoutBroadcasting::class])) {
            $this->disableEventBroadcastingForAllTests();
        }

        if (isset($uses[RecordsHttpCalls::class])) {
            $this->enableHttpRecoding();
            $this->rememberToStopRecording();
        }

        return $uses;
    }

    protected function enableExceptionHandling()
    {
        $this->app->forgetInstance(ExceptionHandler::class);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            public function report(\Exception $e)
            {
            }
            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }
}
