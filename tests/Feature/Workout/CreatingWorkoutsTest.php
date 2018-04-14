<?php

namespace Tests\Feature\Workout;

use App\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class CreatingWorkoutsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	public function canCreateWorkouts()
	{
		return [
			['asDev']
		];
	}

	public function cannotCreateWorkouts()
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
	    	'summary' => 'This workout is an intense workout primarily consisting on heavy barbell complexes.',
	    	'body' => 'You better prepare, or it will woof your light sabers off!'
		], $overrides);
	}

	/** 
	 * @test
	 * @dataProvider canCreateWorkouts 
	 */
	function can_create_workouts($asRole) 
	{
		$this->$asRole()->json('post', '/api/workouts', [
	    	'title' => 'super trooper workout (tm)',
	    	'level' => 'Elite',
	    	'type' => 'CrossFit',
	    	'summary' => 'This workout is an intense workout primarily consisting on heavy barbell complexes.',
	    	'body' => 'You better prepare, or it will woof your light sabers off!'
		])->assertSuccessful();

		$this->assertDatabaseHas('workouts', [
	    	'title' => 'super trooper workout (tm)',
	    	'level' => 'Elite',
	    	'type' => 'CrossFit',
	    	'summary' => 'This workout is an intense workout primarily consisting on heavy barbell complexes.',
	    	'body' => 'You better prepare, or it will woof your light sabers off!'
		]);
	}

	/** 
	 * @test
	 * @dataProvider cannotCreateWorkouts
	 */
	function cannot_create_workouts($asRole, $expectedStatusCode) 
	{
		$this->$asRole()->json('post', '/api/workouts', [
	    	'title' => 'Sallys silly cakewalk',
	    	'level' => 'Beginner',
	    	'type' => 'Hybrid',
	    	'summary' => 'While this may seem as innocent as a baby sheep, i assure you it is not.',
	    	'body' => 'This workout does not put a huge demand immedially but the build up of high frequency will, eventually.'
		])->assertStatus($expectedStatusCode);

		$this->assertDatabaseMissing('workouts', [
	    	'title' => 'Sallys silly cakewalk',
	    	'level' => 'Beginner',
	    	'type' => 'Hybrid',
	    	'summary' => 'While this may seem as innocent as a baby sheep, i assure you it is not.',
	    	'body' => 'This workout does not put a huge demand immedially but the build up of high frequency will, eventually.'
		]);
	}

	/** @test */
	function it_extracts_summary_from_body_when_undefined() 
	{
		$result = $this
			->asDev()
			->json('post', '/api/workouts', $this->params(['body' => str_repeat('a', 255), 'summary' => null]))
			->assertSuccessful()
			->json();

		$this->assertNotNull($summary = array_get($result, 'summary'));

		// Ensure the length equals the default length + 3 dots at the end.
		$this->assertEquals(63, strlen($summary));
		$this->assertTrue(ends_with($summary, '...'));
	} 

	/** @test */
	function cannot_create_workout_with_existing_title() 
	{
		factory(Workout::class)->create(['title' => 'Some workout']);

		$this
			->asDev()
			->json('post', '/api/workouts', $this->params(['title' => 'Some workout']))
			->assertJsonValidationErrors('title')
			->assertSee('The title has already been taken.');
	} 

	/** @test */
	function cannot_create_workout_with_invalid_level() 
	{
		$this
			->asDev()
			->json('POST', '/api/workouts', $this->params(['level' => 'invalid-training-level']))
			->assertJsonValidationErrors('level');
	} 

	/** @test */
	function cannot_create_workout_with_invalid_style() 
	{
		$this
			->asDev()
			->json('POST', '/api/workouts', $this->params(['type' => 'invalid-training-style']))
			->assertJsonValidationErrors('type');
	} 
}
