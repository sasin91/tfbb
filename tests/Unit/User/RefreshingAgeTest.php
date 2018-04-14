<?php

namespace Tests\Unit\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RefreshingAgeTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function it_calculates_the_age_when_possible() 
	{
		$profile = factory(User::class)->create(['born_at' => now()->subYears(20)]);

		$this->assertEquals(20, $profile->age);
	}
}
