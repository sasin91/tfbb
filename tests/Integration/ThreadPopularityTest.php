<?php

namespace Tests\Integration;

use Facades\App\Scores\Popularity;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

/**
 * This test depends on Redis to be running on host system.
 */
class ThreadPopularityTest extends TestCase
{
	protected function setUp()
	{
		parent::setUp();

		Popularity::flush(Thread::class);

		$this->beforeApplicationDestroyed(function () {
			Popularity::flush(Thread::class);
		});
	}

	/** @test */
	function the_popularity_index_is_empty() 
	{
		$this->assertTrue(
			Popularity::list(Thread::class)->isEmpty()
		);
	}

	/** 
	 * @test
	 * @depends the_popularity_index_is_empty
	 */
	function it_increments_the_model_score() 
	{
		$thread = new Thread(['title' => 'Some forum thread title']);

		Popularity::increment($thread);

		$this->assertCount(1, Popularity::list($thread));

		$this->assertEquals($thread->title, Popularity::list($thread)->first()->title);
		$this->assertEquals(1, Popularity::list($thread)->first()->score);
	} 

	/** 
	 * @test
	 * @depends the_popularity_index_is_empty
	 */
	function it_decrements_the_model_score() 
	{
		$thread = new Thread(['title' => 'Some forum thread title']);

		Popularity::decrement($thread);

		$this->assertCount(1, Popularity::list($thread));

	}

	/** 
	 * @test
	 * @depends it_increments_the_model_score
	 * @depends it_decrements_the_model_score
	 */
	function it_lists_the_popular_models_by_score() 
	{
		$threadOne = new Thread(['title' => 'Super popular thread']);
		$threadTwo = new Thread(['title' => 'Another popular thread']);

		Popularity::increment($threadTwo, 5);
		Popularity::increment($threadOne, 10);

		$this->assertEquals([
			[
				'title' => 'Super popular thread',
				'link' => null,
				'score' => 10
			],
			[
				'title' => 'Another popular thread',
				'link' => null,
				'score' => 5
			]
		], Popularity::list(Thread::class)->toArray());		
	} 
}
