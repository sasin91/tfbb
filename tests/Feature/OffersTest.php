<?php

namespace Tests\Feature;

use App\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\WithoutBroadcasting;
use Tests\WithoutQueue;
use Tests\WithoutSearchIndexing;

class OffersTest extends TestCase
{
	use RefreshDatabase, WithoutBroadcasting, WithoutQueue, WithoutSearchIndexing;

	protected function validParams(array $overrides = [])
	{
		return array_merge([
	    	'name' => 'Super duper offer',
	    	'tagline' => 'This offer is an absolute must have.',
	    	'discount' => 50,
	    	'body' => 'Nothing quite beats this offer, it is really that good.',
	    	'poster_url' => 'https://lorempixel.com/640/480/?51732', 
	    	'banner_url' => 'https://lorempixel.com/640/480/?51732',
	    	'offsite_link' => 'http://beatty.net/ut-labore-suscipit-autem-corrupti-sunt',

	        'view' => 'offers.generic'
		], $overrides);
	}

	/** @test */
	function cannot_create_offer_with_invalid_view() 
	{
		$this
			->asDev()
			->json('POST', '/api/offers', $this->validParams(['view' => 'invalid.view']))
			->assertJsonValidationErrors('view');
	}

	/** @test */
	function cannot_update_offer_with_invalid_view() 
	{
		$offer = factory(Offer::class)->create($this->validParams());

		$this
			->asDev()
			->json('PUT', "/api/offers/{$offer->slug}", $this->validParams(['view' => 'invalid.view']))
			->assertJsonValidationErrors('view');
	} 
}
