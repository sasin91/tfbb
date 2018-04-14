<?php

namespace Tests\Feature\Board;

use App\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class UpdatingBoardsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	public function canUpdateBoards()
	{
		return [
			['Developers', 'asDev'],
			['Moderators', 'asModerator'],
		];
	}

	/** 
	 * @test
	 * @dataProvider canUpdateBoards
	 */
	function can_update_boards($role, $method) 
	{
		$board = factory(Board::class)->create();

		$this->$method()->json('PATCH', "/api/boards/{$board->slug}", [
			'name' => 'Hello world',
			'description' => $role
		])->assertSuccessful();

		$this->assertDatabaseHas('boards', [
			'id' => $board->id,
			'name' => 'Hello world',
			'description' => $role
		]);
	}

	/** @test */
	function subscribers_cannot_update_boards() 
	{
		$board = factory(Board::class)->create();

		$this->asSubscriber()->json('PATCH', "/api/boards/{$board->slug}", ['name' => 'Something'])->assertStatus(403);

		$this->assertDatabaseMissing('boards', [
			'name' => 'Something'
		]);
	}

	/** @test */
	function visitors_cannot_update_boards() 
	{
		$board = factory(Board::class)->create();

		$this->json('PATCH', "/api/boards/{$board->slug}", ['name' => 'Something'])->assertStatus(401);

		$this->assertDatabaseMissing('boards', [
			'name' => 'Something'
		]);
	} 
}