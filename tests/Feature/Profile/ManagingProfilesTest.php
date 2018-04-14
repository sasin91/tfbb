<?php

namespace Tests\Feature\Profile;

use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class ManagingProfilesTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	/** @test */
	function creator_can_update_their_profile() 
	{
		$profile = factory(Profile::class)->create();

		$this
			->asSubscriber($profile->creator)
			->json('PATCH', "/api/profiles/{$profile->id}", [
				'goals' => 'some goals',
				'story' => 'some story',
				'training_level' => 'Beginner',
				'training_style' => ''
			])
			->assertSuccessful();

		$this->assertDatabaseHas('profiles', [
			'id' => $profile->id,
			'goals' => 'some goals',
			'story' => 'some story',
			'training_level' => 'Beginner',
			'training_style' => ''
		]);
	} 

	/** @test */
	function creator_can_publish_their_profile() 
	{
		$profile = factory(Profile::class)->create();

		$this
			->asSubscriber($profile->creator)
			->json('POST', "/api/profiles/{$profile->id}/publish")
			->assertSuccessful();

		$this->assertTrue($profile->fresh()->isPublished());
	} 

	/** @test */
	function creator_can_unpublish_their_profile() 
	{
		$profile = factory(Profile::class)->states('published')->create();

		$this
			->asSubscriber($profile->creator)
			->json('POST', "/api/profiles/{$profile->id}/unpublish")
			->assertSuccessful();

		$this->assertFalse($profile->fresh()->isPublished());
	} 

	/** @test */
	function moderators_can_lock_a_profile() 
	{
		$profile = factory(Profile::class)->create();

		$this
			->asModerator()
			->json('POST', "/api/profiles/{$profile->id}/lock")
			->assertSuccessful();

		$this->assertTrue($profile->fresh()->isLocked());
	} 

	/** @test */
	function creator_cannot_unlock_their_profile() 
	{
		$profile = factory(Profile::class)->states('locked')->create();

		$this
			->asSubscriber($profile->creator)
			->json('POST', "/api/profiles/{$profile->id}/unlock")
			->assertStatus(403);

		$this->assertTrue($profile->fresh()->isLocked());
	} 

	/** @test */
	function moderators_can_unlock_a_profile() 
	{
		$profile = factory(Profile::class)->states('locked')->create();

		$this
			->asModerator()
			->json('POST', "/api/profiles/{$profile->id}/unlock")
			->assertSuccessful();

		$this->assertFalse($profile->fresh()->isLocked());
	} 

	/** @test */
	function developers_can_unlock_a_profile() 
	{
		$profile = factory(Profile::class)->states('locked')->create();

		$this
			->asModerator()
			->json('POST', "/api/profiles/{$profile->id}/unlock")
			->assertSuccessful();

		$this->assertFalse($profile->fresh()->isLocked());
	} 
}
