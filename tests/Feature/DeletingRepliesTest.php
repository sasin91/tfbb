<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutQueue;
use Tests\WithoutSearchIndexing;

class DeletingRepliesTest extends TestCase
{
    use RefreshDatabase, WithoutSearchIndexing, WithoutQueue;

    /** @test */
    function developer_can_destroy_any_reply() 
    {
        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->create([
            'thread_id' => $thread->id,
            'title' => 'hello world',
            'body' => 'hello'
        ]);

        $this->asDev()->json('DELETE', "/api/replies/{$reply->hashid}")->assertSuccessful();

        $this->assertSoftDeleted('replies', [
            'thread_id' => $thread->id,
            'title' => 'hello world',
            'body' => 'hello'
        ]);
    } 

    /** @test */
    function subscribed_creator_can_destroy_their_reply() 
    {
    	$creator = factory(User::class)->create();

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->create([
        	'creator_id' => $creator->id,
            'thread_id' => $thread->id,
            'title' => 'hello world',
            'body' => 'hello'
        ]);

        $this->asSubscriber($creator)->json('DELETE', "/api/replies/{$reply->hashid}")->assertSuccessful();

        $this->assertSoftDeleted('replies', [
        	'creator_id' => $creator->id,
            'thread_id' => $thread->id,
            'title' => 'hello world',
            'body' => 'hello'
        ]);
    }

    /** @test */
    function creator_cannot_destroy_their_reply_if_their_subscription_has_expired() 
    {
    	$creator = factory(User::class)->create();

        $thread = factory(Thread::class)->create();
        $reply = factory(Reply::class)->create([
        	'creator_id' => $creator->id,
            'thread_id' => $thread->id,
            'title' => 'hello world',
            'body' => 'hello'
        ]);

        $this->asUser($creator)->json('DELETE', "/api/replies/{$reply->hashid}")->assertStatus(403);

        $this->assertDatabaseHas('replies', [
        	'creator_id' => $creator->id,
            'thread_id' => $thread->id,
            'title' => 'hello world',
            'body' => 'hello'
        ]);
    }

    /** @test */
    function guest_cannot_destroy_a_reply() 
    {
    	$reply = factory(Reply::class)->create();

        $this->asGuest()->json('DELETE', "/api/replies/{$reply->hashid}")->assertStatus(401);
    }

    /** @test */
    function a_regular_user_cannot_destroy_a_reply() 
    {
    	$reply = factory(Reply::class)->create();

        $this->asUser()->json('DELETE', "/api/replies/{$reply->hashid}")->assertStatus(403);
    } 

    /** @test */
    function a_subscriber_cannot_delete_anothers_reply() 
    {
    	$reply = factory(Reply::class)->create();

        $this->asSubscriber()->json('DELETE', "/api/replies/{$reply->hashid}")->assertStatus(403);
    } 
}
