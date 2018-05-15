<?php

namespace App\Services\USDA;

use App\Food;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class NDB
{
	/**
	 * Guzzle http client configured for the resource.
	 * 
	 * @var \GuzzleHttp\Client
	 */
	protected $client;

	/**
	 * The NDB api_key
	 * 
	 * @var string
	 */
	private $secret;

	/**
	 * Construct the NDB client
	 * 	
	 * @param string $secret 
	 */
	public function __construct($secret)
	{
		$this->secret = $secret;

		$this->createClient($secret);
	}

	/**
	 * Create the underlying HTTP client instance.
	 * 
	 * @param  string $secret 
	 */
	protected function createClient(string $secret)
	{
		$handlers = tap(HandlerStack::create(), function ($handlerStack) {
            $handlerStack->push(resolve('Guzzle cache'), 'cache');
        });

		$this->client = new Client([
			'handler' => $handlers,
			'base_uri' => 'https://api.nal.usda.gov/ndb/',
			'headers' => [
				'X-Api-Key' => $secret,
				'Accept' => 'application/json',
				'Content-Type' => 'application/json'
			]
		]);
	}

	/**
	 * Search the NDB api for given value
	 * 
	 * @param  string      $value 
	 * @param  int|integer $page  
	 * @param  int|integer $take  
	 * 
	 * @return array
	 */
	public function search(string $value, int $page = 0, int $take = 25)
	{		
		$response = $this->client->get('search', [
			'query' => [
				'q' => $value,
				'offset' => $page,
				'max' => $take
			],
		]);

		$results = json_decode($response->getBody()->getContents(), true);

		return array_get($results, 'list.item', []);
	}

	/**
	 * Fetch the raw report for given ndbno
	 * 
	 * @param  string $id NDBNo
	 * @return array
	 */
	public function fetchReport($id)
	{
		$response = $this->client->post('reports/V2', [
			'json' => [
				'ndbno' => (string)$id,
				'type' => 'b',
			]
		]);

		$result = json_decode($response->getBody()->getContents(), true);

		return array_get($result, 'report', []);
	}

	/**
	 * Find and resolve given id to a Food model instance.
	 * 
	 * @param  string $id [The ndb no]
	 * @return \App\Food
	 */
	public function food($id)
	{
		return Food::fromNDB(
			$this->fetchReport($id)['food']
		);
	}
}