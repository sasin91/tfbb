<?php

namespace Tests\Feature\Diet;

use App\Diet;
use App\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class AttachingMealsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing;

	/** @test */
	function developers_can_attach_meals_to_a_diet() 
	{
		$diet = factory(Diet::class)->create();

		$meals = factory(Meal::class)->times(2)->create();

		$this->asDev()->json('POST', "/api/diets/{$diet->slug}/meals", ['meals' => [1,2]])->assertSuccessful();

		$meals->each(function ($meal) use ($diet) {
			$this->assertTrue($diet->meals->contains($meal));

			$this->assertDatabaseHas('dishes', [
				'diet_id' => $diet->id,
				'meal_id' => $meal->id
			]);
		});
	} 
}
