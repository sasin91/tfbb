<?php

namespace Tests\Feature;

use App\Board;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutQueue;
use Tests\WithoutSearchIndexing;

class CreatingThreadsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutQueue;

	/** @test */
	function unsubscribed_cannot_create_a_thread() 
	{
		$user = factory(User::class)->create();
  		$board = factory(Board::class)->create();

    	$this->actingAs($user)->json('POST', "/api/boards/{$board->slug}/threads", [
    		'title' => 'hello world',
    		'body' => 'this should not be created.'
    	])->assertStatus(403);

    	$this->assertDatabaseMissing('threads', [
    		'board_id' => $board->id,
    		'title' => 'hello world',
    		'body' => 'this should not be created.'
    	]);
	} 

    /** @test */
    function subscriber_can_create_a_thread() 
    {
    	$this->disableExceptionHandling();

    	$board = factory(Board::class)->create();

    	$this->asSubscriber()->json('POST', "/api/boards/{$board->slug}/threads", [
    		'title' => 'hello world',
    		'body' => 'this is a test thread.'
    	])->assertSuccessful();

    	$this->assertDatabaseHas('threads', [
    		'board_id' => $board->id,
    		'title' => 'hello world',
    		'body' => 'this is a test thread.'
    	]);
    }

    public function invalidThreadTitles()
    {
        return [
            ['aaaaa'],
            [''],
            [null],
        ];
    }

    /**
     * @test
     * @dataProvider invalidThreadTitles
     */
    function validation_fails_when_name_is_invalid($title) 
    {
        $board = factory(Board::class)->create();

        $this->asDev()->json('POST', "/api/boards/{$board->slug}/threads", [
            'title' => $title
        ])->assertJsonValidationErrors('title');
    } 

}