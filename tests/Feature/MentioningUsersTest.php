<?php

namespace Tests\Feature;

use App\Comment;
use App\Notifications\User\YouWereMentioned;
use App\Reply;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class MentioningUsersTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	public function mentionees()
	{
		return [
			['John', '@John'],
			['John_doe', '@John_doe'],
			['John_doe-san', '@John_doe-san'],

			['John', '@John!'],
			['John', '@John?'],
			['John_doe', '@John_doe!?'],
			['John_doe', '@John_doe,'],
			['John_doe', '@John_doe*']
		];
	}

	/** 
	 * @test
	 * 
	 * @dataProvider mentionees
	 */
	function it_dispatches_notifications_to_mentioned_users_in_reply_body($name, $tag) 
	{
		Notification::fake();	
		$john = factory(User::class)->create(['nickname' => $name]);

		factory(Reply::class)->create(['body' => "Hey {$tag}"]);

		Notification::assertSentTo($john, YouWereMentioned::class);
	} 

	/** 
	 * @test
	 * 
	 * @dataProvider mentionees
	 */
	function it_dispatches_notifications_to_mentioned_users_in_comment_body($name, $tag) 
	{
		Notification::fake();	
		$john = factory(User::class)->create(['nickname' => $name]);

		factory(Comment::class)->create(['body' => "Hey {$tag}"]);

		Notification::assertSentTo($john, YouWereMentioned::class);
	} 
}
