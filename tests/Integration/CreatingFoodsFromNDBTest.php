<?php

namespace Tests\Integration;

use App\Food;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\RecordsHttpCalls;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class CreatingFoodsFromNDBTest extends TestCase
{
	const PEANUT_BUTTER = "45038484";

	use RefreshDatabase, RecordsHttpCalls, WithoutSearchIndexing;

    /** @test */
    function can_create_a_new_food_by_querying_USDA() 
    {
    	$this->startRecording('NDB::PEANUT_BUTTER');

    	try {
    		$food = resolve('NDB')->food(self::PEANUT_BUTTER);
    	} catch(RequestException $e) {
            $this->fail($e->getResponse()->getBody()->getContents());
    	}

    	$this->assertDatabaseHas('foods', [
    		'id' => $food->id,
		    "name" => "SHOPRITE, ORGANIC CHUNKY PEANUT BUTTER, PEANUT, BUTTER, UPC: 041190043538",
		    "carbohydrate" => "21.88 g",
		    "energy" => "594 kcal",
		    "fat" => "594 kcal",
		    "protein" => "25.00 g"
    	]);
    } 
}