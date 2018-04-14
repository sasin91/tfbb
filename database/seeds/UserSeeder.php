<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Laravel\Spark\Spark;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedDevelopers();
        $this->seedSubscribers();
    }

    protected function seedDevelopers()
    {
    	foreach (Spark::$developers as $email) {
    		$user = factory(App\User::class)->create(['email' => $email]);

            $this->withSubscription($user);
    	}
    }

    protected function seedSubscribers()
    {
        $subscribers = ['john@example.com'];

        foreach ($subscribers as $email) {
            $user = factory(App\User::class)->create(['email' => $email]);

            $this->withSubscription($user);
        }
    }

    protected function withSubscription($user, $subscription = null, $plan = null)
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
}
