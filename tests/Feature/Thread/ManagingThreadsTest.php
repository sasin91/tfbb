<?php

namespace Tests\Feature\Thread;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutQueue;
use Tests\WithoutSearchIndexing;

class ManagingThreadsTest extends TestCase
{
	use RefreshDatabase, WithoutBroadcasting, WithoutSearchIndexing, WithoutQueue;

	public function canManageThreads()
	{
		return [
			['asDev'],
			['asModerator']
		];
	}

	/** 
	 * @test
	 * @dataProvider canManageThreads
	 */
	function can_lock_a_thread($role) 
	{
		$thread = factory(Thread::class)->states('locked')->create();

		$this->$role()
		    ->json('POST', "/api/threads/{$thread->hashid}/lock")
		    ->assertSuccessful();

		$this->assertNotNull($thread->locked_at);
	} 

	/** 
	 * @test
	 * @dataProvider canManageThreads
	 */
	function can_unlock_a_thread($role) 
	{
		$thread = factory(Thread::class)->states('locked')->create();

		$this->$role()
		    ->json('POST', "/api/threads/{$thread->hashid}/unlock")
		    ->assertSuccessful();

		$this->assertDatabaseHas('threads', [
			'id' => $thread->id,
			'locked_at' => null
		]);
	}

	/** @test */
	function creator_cannot_lock_their_thread() 
	{
		$user = factory(User::class)->create();
		$thread = factory(Thread::class)->create(['creator_id' => $user->id]);

		$this->actingAs($user)->json('POST', "/api/threads/{$thread->hashid}/lock")->assertStatus(403);
		$this->asSubscriber($user)->json('POST', "/api/threads/{$thread->hashid}/lock")->assertStatus(403);

		$this->assertDatabaseHas('threads', [
			'creator_id' => $user->id,
			'id' => $thread->id,
			'locked_at' => null
		]);
	}

	/** @test */
	function creator_cannot_unlock_their_thread() 
	{
		$user = factory(User::class)->create();
		$thread = factory(Thread::class)->states('locked')->create(['creator_id' => $user->id]);

		$this->actingAs($user)->json('POST', "/api/threads/{$thread->hashid}/unlock")->assertStatus(403);
		$this->asSubscriber($user)->json('POST', "/api/threads/{$thread->hashid}/unlock")->assertStatus(403);

		$this->assertNotNull($thread->locked_at);
	} 

	/** @test */
	function guest_cannot_lock_a_thread() 
	{
		$thread = factory(Thread::class)->create();

		$this->asGuest()->json('POST', "/api/threads/{$thread->hashid}/lock")->assertStatus(401);

		$this->assertDatabaseHas('threads', [
			'id' => $thread->id,
			'locked_at' => null
		]);
	}

	/** @test */
	function guest_cannot_unlock_a_thread() 
	{
		$thread = factory(Thread::class)->states('locked')->create();

		$this->asGuest()->json('POST', "/api/threads/{$thread->hashid}/unlock")->assertStatus(401);

		$this->assertNotNull($thread->locked_at);
	}

	/** @test */
	function another_subscriber_cannot_unlock_a_thread() 
	{
		$thread = factory(Thread::class)->states('locked')->create();

		$this->asSubscriber()->json('POST', "/api/threads/{$thread->hashid}/unlock")->assertStatus(403);

		$this->assertNotNull($thread->locked_at);
	} 

	/** @test */
	function another_subscriber_cannot_lock_a_thread() 
	{
		$thread = factory(Thread::class)->create();

		$this->asSubscriber()->json('POST', "/api/threads/{$thread->hashid}/lock")->assertStatus(403);

		$this->assertDatabaseHas('threads', [
			'id' => $thread->id,
			'locked_at' => null
		]);
	} 
}