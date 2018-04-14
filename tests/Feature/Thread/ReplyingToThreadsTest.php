<?php

namespace Tests\Feature\Thread;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Spark\Spark;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutQueue;
use Tests\WithoutSearchIndexing;

class ReplyingToThreadsTest extends TestCase
{
	use RefreshDatabase, WithFaker, WithoutQueue, WithoutBroadcasting, WithoutSearchIndexing;

    public function canReplyToThread()
    {
    	return [
    		['asDev', 'lee@LeeHayward.com'],
    		['asModerator'],
    		['asSubscriber']
    	];
    }

	public function cannotReplyToThread()
	{	
		return [
			['asUser', Response::HTTP_FORBIDDEN],
			['asGuest', Response::HTTP_UNAUTHORIZED]
		];	
	}

	public function cannotReplyToALockedThread()
	{
		return [
			['asGuest', Response::HTTP_UNAUTHORIZED],
			['asUser', Response::HTTP_FORBIDDEN],
			['asSubscriber', Response::HTTP_FORBIDDEN],
		];
	}

	public function canReplyToALockedThread()
	{
		return [
			['asModerator'],
			['asDev']
		];
	}

	public function invalidReplyTitles()
	{
		return [
			['aaaaa'],
		];
	}

	public function invalidReplyBodies()
	{
		return [
			['aaaaa'],
			[''],
			[null],
		];
	}

	/**
	 * @test
	 * @dataProvider canReplyToThread
	 */
	function can_reply_to_thread($role, $email = null) 
	{
		$thread = factory(Thread::class)->create();
		$user = factory(User::class)->create(['email' => $email ?? $this->faker()->safeEmail()]);

		$this->$role($user)->json('POST', "/api/threads/{$thread->hashid}/replies", [
			'title' => "{$role} checking in.",
			'body' => 'hello world'
		])->assertSuccessful();

		$this->assertDatabaseHas('replies', [
			'creator_id' => $user->id,
			'thread_id' => $thread->id,
			'title' => "{$role} checking in.",
			'body' => 'hello world'
		]);
	}

	/** 
	 * @test
	 * @dataProvider cannotReplyToThread
	 */
	function cannot_reply_to_thread($role, $statusCode) 
	{
		$thread = factory(Thread::class)->create();
		$user = factory(User::class)->create();
		
		$this->$role($user)->json('POST', "/api/threads/{$thread->hashid}/replies", [
			'title' => "{$role} checking in.",
			'body' => 'hello world'
		])->assertStatus($statusCode);

		$this->assertDatabaseMissing('replies', [
			'creator_id' => $user->id,
			'thread_id' => $thread->id,
			'title' => "{$role} checking in.",
			'body' => 'hello world'
		]);
	}

	/** 
	 * @test
	 * @dataProvider CannotReplyToALockedThread
	 */
	function cannot_reply_to_a_locked_thread($role, $status) 
	{
		$thread = factory(Thread::class)->states('locked')->create();

		$this->$role()->json('POST', "/api/threads/{$thread->hashid}/replies", [
			'title' => 'hello',
			'body' => "{$role} got through!"
		])->assertStatus($status);

		$this->assertDatabaseMissing('replies', [
			'thread_id' => $thread->id,
			'title' => 'hello',
			'body' => "{$role} got through!"
		]);
	}

	/** 
	 * @test
	 * @dataProvider CanReplyToALockedThread
	 */
	function can_reply_to_a_locked_thread($role) 
	{
		$thread = factory(Thread::class)->states('locked')->create();

		$this->$role()->json('POST', "/api/threads/{$thread->hashid}/replies", [
			'title' => 'hello',
			'body' => "{$role} got through!"
		])->assertSuccessful();

		$this->assertDatabaseHas('replies', [
			'thread_id' => $thread->id,
			'title' => 'hello',
			'body' => "{$role} got through!"
		]);
	} 

	/** 
	 * @test
	 * @dataProvider invalidReplyTitles
	 */
	function validation_fails_when_title_is_invalid($title) 
	{
		$thread = factory(Thread::class)->create();

		$this->asDev()
			->json('POST', "/api/threads/{$thread->hashid}/replies", ['title' => $title])
			->assertJsonValidationErrors('title');
	} 

	/** 
	 * @test
	 * @dataProvider invalidReplyBodies
	 */
	function validation_fails_when_body_is_invalid($body) 
	{
		$thread = factory(Thread::class)->create();

		$this->asDev()
			->json('POST', "/api/threads/{$thread->hashid}/replies", ['body' => $body])
			->assertJsonValidationErrors('body');
	}
}