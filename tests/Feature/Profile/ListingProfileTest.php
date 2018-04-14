<?php

namespace Tests\Feature\Profile;

use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class ListingProfileTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	/** 
	 * @test
	 */
	function does_not_list_unpublished_profiles() 
	{
		factory(Profile::class)->create(['goals' => 'Get lean for beach season.']);
		$publishedProfile = factory(Profile::class)->states('published')->create(['goals' => 'Look like a greek god.']);

		$data = $this
			->asSubscriber($publishedProfile->creator)
			->json('GET', '/api/profiles')
			->assertSuccessful()
			->assertSee('Look like a greek god.')
			->assertDontSee('Get lean for beach season.')
			->json()['data'];

		$this->assertCount(1, $data);
	}

	/** @test */
	function other_subscribers_cannot_view_an_unpublished_profile() 
	{
		$profile = factory(Profile::class)->create();

		$this->asSubscriber()->json('GET', "/api/profiles/{$profile->id}")->assertStatus(403);
	} 

	/** @test */
	function moderator_cannot_view_an_unpublished_profile() 
	{
		$profile = factory(Profile::class)->create();

		$this->asModerator()->json('GET', "/api/profiles/{$profile->id}")->assertStatus(403);
	} 

	/** @test */
	function creator_can_view_their_profile_when_unpublished() 
	{
		$user = factory(User::class)->create();
		$profile = factory(Profile::class)->create(['creator_id' => $user->id, 'goals' => 'something']);

		$this->asSubscriber($user)
			->json('GET', "/api/profiles/{$profile->id}")
			->assertSuccessful()
			->assertSee('something');
	} 
}
