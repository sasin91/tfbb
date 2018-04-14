<?php

namespace Tests\Feature\Board;

use App\Board;
use App\Team;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Spark\Contracts\Interactions\Settings\Teams\AddTeamMember;
use Laravel\Spark\Spark;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutSearchIndexing;

class CreatingBoardsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing, WithoutBroadcasting;

	public function invalidBoardNames()
	{
		return [
			['aaaaa'],
			[''],
			[null],
		];
	}

	/**
	 * @test
	 * @dataProvider invalidBoardNames
	 */
	function validation_fails_when_name_is_invalid($name) 
	{
		$this->asDev()->json('POST', '/api/boards', [
			'name' => $name
		])->assertJsonValidationErrors('name');
	} 

	public function canStoreBoards()
	{
		return [
			['Developers', 'asDev'],
			['Moderators', 'asModerator'],
		];
	}	

	/** 
	 * @test
	 * @dataProvider canStoreBoards
	 */
	function can_store_new_boards($role, $method) 
	{
		$this->disableExceptionHandling();

		$this->$method()->json('POST', '/api/boards', [
			'name' => 'Some board name',
			'description' => $role
		])->assertSuccessful();

		$this->assertDatabaseHas('boards', [
			'name' => 'Some board name',
			'description' => $role
		]);
	} 

	/** @test */
	function subscribers_cannot_store_new_boards() 
	{
		$this->asSubscriber()->post('/api/boards', ['name' => 'Something'])->assertStatus(403);

		$this->assertDatabaseMissing('boards', [
			'name' => 'Something'
		]);
	}

	/** @test */
	function visitors_cannot_store_new_boards() 
	{
		$this->post('/api/boards', ['name' => 'Something'])->assertRedirect('/login');

		$this->assertDatabaseMissing('boards', [
			'name' => 'Something'
		]);
	} 
}
