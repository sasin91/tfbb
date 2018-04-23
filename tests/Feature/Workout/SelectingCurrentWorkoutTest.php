<?php

namespace Tests\Feature\Workout;

use App\User;
use App\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class SelectingCurrentWorkoutTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	/** @test */
	function can_select_a_workout_to_attend() 
	{
		$workout = factory(Workout::class)->create(['title' => 'Old-school strength training']);

	   	$this
		   	->asSubscriber($user = factory(User::class)->create())
		   	->post('/workout', ['workout_id' => $workout->id])
		   	->assertRedirect()
		   	->assertSessionHas('status', "You're now following Old-school strength training.");

		$this->assertTrue($user->currentWorkout->is($workout), "User did not start workout.");
	}    
}