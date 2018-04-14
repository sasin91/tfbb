<?php

namespace Tests\Feature\Board;

use App\Board;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class DeletingBoardsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	public function canDestroyBoards()
	{
		return [
			['Developers', 'asDev']
		];
	}

	/** 
	 * @test
	 * @dataProvider canDestroyBoards
	 */
	function can_destroy_boards($role, $method) 
	{
		$board = factory(Board::class)->create();

		$this->$method()->json('DELETE', "/api/boards/{$board->slug}")->assertSuccessful();

		$this->assertSoftDeleted('boards', [
			'id' => $board->id,
		]);
	} 

	/** @test */
	function visitors_cannot_delete_boards() 
	{
		$board = factory(Board::class)->create();

		$this->json('DELETE', "/api/boards/{$board->slug}")->assertStatus(401);

		$this->assertDatabaseHas('boards', ['id' => $board->id]);
	} 
}