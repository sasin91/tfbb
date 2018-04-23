<?php

namespace Tests\Feature\Diet;

use App\User;
use App\Diet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class SelectingCurrentDietTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	/** @test */
	function can_select_a_diet_to_attend() 
	{
		$diet = factory(Diet::class)->create(['title' => '4 weeks to shredded']);

	   	$this
		   	->asSubscriber($user = factory(User::class)->create())
		   	->post('/diet', ['diet_id' => $diet->id])
		   	->assertRedirect()
		   	->assertSessionHas('status', "You're now following 4 weeks to shredded.");

		$this->assertTrue($user->currentDiet->is($diet), "User did not start diet.");
	}    
}