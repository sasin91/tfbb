<?php

namespace Tests\Unit\Score;

use Facades\App\Scores\Popularity;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PopularityScoreTest extends TestCase
{	
	/**
	 * the keys are expected to be {$environment}_popular_{plural($input)}
	 * @return array
	 */
	public function keys()
	{
		return [
			[
				Thread::class, 'testing_popular_threads',
				'threads', 'testing_popular_threads',
				'thread', 'testing_popular_threads'
			]
		];
	}

	/** 
	 * @test
	 * @dataProvider keys
	 */
	function it_builds_the_correct_key($input, $expected) 
	{
		$this->assertEquals($expected, Popularity::key($input));
	} 
}
