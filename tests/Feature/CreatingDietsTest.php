<?php

namespace Tests\Feature;

use App\Diet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class CreatingDietsTest extends TestCase
{
	use RefreshDatabase, WithoutSearchIndexing;

	protected function validParams(array $overrides)
	{
		return array_merge([
			'goal' => 'Fat loss', 
			'style' => 'High protein',
			'title' => 'Some diet title',
			'slug' => 'some-diet-title',
			'summary' => 'this diet is awesome because...', 
			'body' => 'Diet consists of bla bla bla...',
	        'banner_url' => 'http://via.placeholder.com/350x150'
		], $overrides);
	}

	/** @test */
	function cannot_create_diet_with_existing_title()
	{
		factory(Diet::class)->create(['title' => 'diet title']);

		$this->asDev()->json('POST', '/api/diets', $this->validParams(['title' => 'diet title']))->assertJsonValidationErrors('title');
	}

	/** @test */
	function cannot_create_diet_with_existing_slug()
	{
		factory(Diet::class)->create(['title' => 'Sluggy slug', 'slug' => 'sluggy-slug']);

		$this->asDev()->json('POST', '/api/diets', $this->validParams(['title' => 'Sluggy slug', 'slug' => 'sluggy-slug']))->assertJsonValidationErrors('slug');	
	}
}
