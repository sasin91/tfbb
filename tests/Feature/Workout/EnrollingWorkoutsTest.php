<?php

namespace Tests\Feature\Workout;

use App\User;
use App\Workout;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnrollingWorkoutsTest extends TestCase
{
    use RefreshDatabase;
    
    public function canEnrollAWorkout()
    {
        return [
            ['asDeveloper'],
            ['asSubscriber'],
            ['asModerator'],
            ['asDeveloper']
        ];
    }

    public function cannotEnrollAWorkout()
    {
        return [
            ['asGuest', Response::HTTP_UNAUTHORIZED],
            ['asUser', Response::HTTP_FORBIDDEN]
        ];
    }

    /** 
     * @test
     * @dataProvider canEnrollAWorkout
     */
    public function testCanEnrollAWorkout($role)
    {
        $user = factory(User::class)->create();

        $workout = factory(Workout::class)->create();
        
        $this
            ->$role($user)
            ->json('POST', route('workouts.enrollment.store', $workout))
            ->assertSuccessful();

        $this->assertDatabaseHas('enrollments', [
            'enrollable_type' => Workout::class,
            'enrollable_id' => $workout->id,
            'user_id' => $user->id,
            'progress' => 0,
            'finished_at' => null
        ]);
    }
    
    /** 
     * @test
     * @dataProvider cannotEnrollAWorkout
     */
    public function testCannotEnrollAWorkout($role, $status)
    {
        $user = factory(User::class)->create();

        $workout = factory(Workout::class)->create();
        
        $this
            ->$role($user)
            ->json('POST', route('workouts.enrollment.store', $workout))
            ->assertStatus($status);

        $this->assertDatabaseMissing('enrollments', [
            'enrollable_type' => Workout::class,
            'enrollable_id' => $workout->id,
            'user_id' => $user->id,
            'progress' => 0,
            'finished_at' => null
        ]);
    }
}
