<?php

namespace Tests\Feature\Board;

use App\Board;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class ListingThreadsTest extends TestCase
{
	use RefreshDatabase, WithoutBroadcasting, WithoutSearchIndexing;

	public function canListThreads()
	{
		return [
			['asDev'],
			['asModerator'],
			['asSubscriber']
		];
	}

	public function cannotListThreads()
	{
		return [
			['asGuest', 401],
			['asUser', 403]
		];
	}

	/** 
	 * @test
	 * @dataProvider canListThreads
	 */
	function can_list_threads($role) 
	{
		$board = factory(Board::class)->create();
		factory(Thread::class)->create(['board_id' => $board->id]);

		$threads = $this->$role()
			->json('GET', "/api/boards/{$board->slug}/threads")
			->assertSuccessful()
			->json()['data'];

		$this->assertCount(1, $threads);
	} 

	/** 
	 * @test
	 * @dataProvider cannotListThreads
	 */
	function cannot_list_threads($role, $expectedStatusCode) 
	{
		$board = factory(Board::class)->create();

		$this->$role()
			->json('GET', "/api/boards/{$board->slug}/threads")
			->assertStatus($expectedStatusCode);
	} 
}