<?php

namespace Tests\Feature\Workout;

use App\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class UpdatingWorkoutsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	public function canUpdateWorkouts()
	{
		return [
			['asDev']
		];
	}

	public function cannotUpdateWorkouts()
	{
		return [
			['asGuest', Response::HTTP_UNAUTHORIZED],
			['asUser', Response::HTTP_FORBIDDEN],
			['asSubscriber', Response::HTTP_FORBIDDEN],
			['asModerator', Response::HTTP_FORBIDDEN]
		];
	}

	public function params(array $overrides = [])
	{
		return array_merge([
	    	'title' => 'super trooper workout (tm)',
	    	'level' => 'Elite',
	    	'type' => 'CrossFit',
	    	'summary' => 'This workout is an intense one primarily consisting of heavy barbell complexes.',
	    	'body' => 'You better prepare, or it will woof your light sabers off!'
		], $overrides);
	}

	/** 
	 * @test
	 * @dataProvider canUpdateWorkouts 
	 */
	function can_update_workouts($asRole) 
	{
		$workout = factory(Workout::class)->create();

		$this->$asRole()->json('put', "/api/workouts/{$workout->slug}", [
	    	'title' => 'super trooper workout (tm)',
	    	'level' => 'Elite',
	    	'type' => 'CrossFit',
	    	'summary' => 'This workout is an intense one primarily consisting of heavy barbell complexes.',
	    	'body' => 'You better prepare, or it will woof your light sabers off!'
		])->assertSuccessful()->assertJson([
	    	'title' => 'super trooper workout (tm)',
	    	'level' => 'Elite',
	    	'type' => 'CrossFit',
	    	'summary' => 'This workout is an intense one primarily consisting of heavy barbell complexes.',
	    	'body' => 'You better prepare, or it will woof your light sabers off!'
		]);

		$this->assertDatabaseHas('workouts', [
			'id' => $workout->id,
	    	'title' => 'super trooper workout (tm)',
	    	'level' => 'Elite',
	    	'type' => 'CrossFit',
	    	'summary' => 'This workout is an intense one primarily consisting of heavy barbell complexes.',
	    	'body' => 'You better prepare, or it will woof your light sabers off!'
		]);
	}

	/** 
	 * @test
	 * @dataProvider cannotUpdateWorkouts
	 */
	function cannot_update_workouts($asRole, $expectedStatusCode) 
	{
		$workout = factory(Workout::class)->create();

		$this->$asRole()->json('patch', "/api/workouts/{$workout->slug}", [
	    	'title' => 'Sallys silly cakewalk',
	    	'level' => 'Beginner',
	    	'type' => 'Mixed',
	    	'summary' => 'While this may seem as innocent as a baby sheep, i assure you it is not.',
	    	'body' => 'This workout does not put a huge demand immedially but the build up of high frequency will, eventually.'
		])->assertStatus($expectedStatusCode);

		$this->assertDatabaseMissing('workouts', [
			'id' => $workout->id,
	    	'title' => 'Sallys silly cakewalk',
	    	'level' => 'Beginner',
	    	'type' => 'Mixed',
	    	'summary' => 'While this may seem as innocent as a baby sheep, i assure you it is not.',
	    	'body' => 'This workout does not put a huge demand immedially but the build up of high frequency will, eventually.'
		]);
	}

	/** @test */
	function it_extracts_summary_from_body_when_undefined() 
	{
		$workout = factory(Workout::class)->create();

		$result = $this
			->asDev()
			->json('put', "/api/workouts/{$workout->slug}", ['summary' => null])
			->assertSuccessful()
			->json();

		$this->assertNotNull($summary = array_get($result, 'summary'));
		$this->assertEquals($workout->generateSummary(), $summary);
	} 

	/** @test */
	function cannot_update_workout_with_existing_title() 
	{
		factory(Workout::class)->create(['title' => 'Some workout']);
		$workout = factory(Workout::class)->create();

		$this
			->asDev()
			->json('put', "/api/workouts/{$workout->slug}", ['title' => 'Some workout'])
			->assertJsonValidationErrors('title')
			->assertSee('The title has already been taken.');
	} 
}
