<?php

namespace App\Services\Wger;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

class Wger
{
	/**
	 * The authorization token
	 * 	
	 * @var string
	 */
	protected $secret;

	/**
	 * Array of content transformers for preparing raw data before pushing to the API.
	 * 
	 * @var array
	 */
	protected $transformers = [
		'exercise' => Transformers\ExerciseTransformer::class
	];

	/**
	 * Wger constructor
	 * 
	 * @param string $authToken [The authorization token generated at https://wger.de/en/user/api-key]
	 */
	public function __construct($authToken) 
	{
		$this->secret = $authToken;
	}

	/**
	 * Get the HTTP Client for the Wger api.
	 *
	 * @param  string $resource
	 * @return \GuzzleHttp\Client
	 */
	public function resource(string $resource)
	{
		return new WgerAPIResource($resource, $this->secret);
	}

	/**
	 * Add a transformer
	 * 
	 * @param string $resource 
	 * @param callable $callable [Callback, method reference or class implementing __invoke]
	 */
	public function addTransformer(string $resource, $callable)
	{
		$this->transformers[$resource] = $callable;
	}

	/**
	 * Get a transformer for given resource.
	 * 
	 * @param  string $forResource 
	 * @return callable              
	 */
	public function transformer(string $forResource)
	{
		$transformer = array_get($this->transformers, $forResource, function ($content) {
			return $content;
		});

		if (is_string($transformer)) {
			return new $transformer;
		}

		return $transformer;
	}
}