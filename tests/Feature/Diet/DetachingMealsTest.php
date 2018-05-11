<?php

namespace Tests\Feature\Diet;

use App\Diet;
use App\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class DetachingMealsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing;

	/** @test */
	function developers_can_detach_meals_to_a_diet() 
	{
		$diet = factory(Diet::class)->create();
		$diet->meals()->sync(
			$meals = factory(Meal::class)->times(2)->create()
		);

		$this->asDev()->json('DELETE', "/api/diets/{$diet->slug}/meals", ['meals' => [1,2]])->assertSuccessful();

		$meals->each(function ($meal) use ($diet) {
			$this->assertFalse($diet->meals->contains($meal));

			$this->assertDatabaseMissing('dishes', [
				'diet_id' => $diet->id,
				'meal_id' => $meal->id
			]);
		});
	}

	/** @test */
	function cannot_detach_unattached_meal() 
	{
		$diet = factory(Diet::class)->create();
		$meal = factory(Meal::class)->create();

		$this->asDev()->json('DELETE', "/api/diets/{$diet->slug}/meals", ['meals' => [$meal->id]])->assertJsonValidationErrors('meals.0');
	}  
}
