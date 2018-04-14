<?php

namespace Tests\Feature\Profile;

use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class CreatingProfileTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	public function canCreateAProfile()
	{
		return [
			['asUser'],
			['asSubscriber'],
			['asModerator'],
			['asDev']
		];
	}

	public function validParameters(array $overrides = [])
	{
		return array_merge([
	    	'story' => 'Just another happy chap.',
	    	'goals' => 'Get lean & muscular.',
	        'training_level' => 'Intermediate',
	        'training_style' => 'BodyBuilding'
		], $overrides);
	}

	/** @test */
	function cannot_create_a_profile_when_already_having_one() 
	{
		$user = factory(User::class)->create();

		factory(Profile::class)->create(['creator_id' => $user->id]);

		$this
			->asSubscriber($user)
			->json('POST', '/api/profiles', $this->validParameters())
			->assertJsonValidationErrors('profile')
			->assertSee(__('User already have a profile.'));
	}

	/** @test */
	function dev_cannot_create_a_profile_for_a_user_that_already_have_one() 
	{
		$user = factory(User::class)->create();

		factory(Profile::class)->create(['creator_id' => $user->id]);

		$this
			->asDev()
			->json('POST', '/api/profiles', $this->validParameters(['user_id' => $user->id]))
			->assertJsonValidationErrors('profile')
			->assertSee(__('User already have a profile.'));
	} 

	/** @test */
	function dev_can_create_a_profile_for_a_user() 
	{
		$user = factory(User::class)->create();

		$this
			->asDev()
			->json('POST', '/api/profiles', $this->validParameters(['user_id' => $user->id, 'goals' => 'whatever']))
			->assertSuccessful();

		$this->assertDatabaseHas('profiles', [
			'creator_id' => $user->id,
			'goals' => 'whatever'
		]);
	} 

	/** @test */
	function guests_cannot_create_a_profile() 
	{
		$this->asGuest()->json('POST', '/profile', $this->validParameters())->assertStatus(401);
		$this->asGuest()->json('POST', '/api/profiles', $this->validParameters())->assertStatus(401);
	} 

	/** 
	 * @test
	 * @dataProvider canCreateAProfile
	 */
	function can_create_a_profile($asRole) 
	{
		$user = factory(User::class)->create();

		$this
			->$asRole($user)
			->json('POST', '/api/profiles', $this->validParameters(['story' => 'John doe the chap from nowhere.']))
			->assertSuccessful();

		$this->assertDatabaseHas('profiles', [
			'creator_id' => $user->id,
			'story' => 'John doe the chap from nowhere.'
		]);
	}
}
