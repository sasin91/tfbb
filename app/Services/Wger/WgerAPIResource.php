<?php

namespace App\Services\Wger;

use App\Services\RemoteAPI;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Support\Str;

class WgerAPIResource
{
	/**
	 * Guzzle http client configured for the resource.
	 * 
	 * @var \GuzzleHttp\Client
	 */
	public $client;

	/**
	 * The resource name
	 * 
	 * @var string
	 */
	public $name;

	/**
	 * Create a new resource for the remote API.
	 * 
	 * @param string $resource
	 * @param string $secret  
	 */
	public function __construct(string $resource, string $secret)
	{
		$this->name = Str::lower($resource);

		$this->createClient($secret);
	}

	/**
	 * Delegate calls to the underlying client
	 * 
	 * @param  string $method     
	 * @param  array  $parameters 
	 * @return PromiseInterface | mixed
	 */
	public function __call($method, $parameters = [])
	{
		list ($id, $data) = array_pad($parameters, 2, null);

		if ($method == 'post') {
			return $this->client->$method($this->prepare($data));
		}

		if (in_array($method, ['put', 'patch'])) {
			return $this->client->$method(
				$id,
				$this->prepare($data)
			);
		}

		return $this->client->$method((string)$id, $data);
	}

	/**
	 * Prepare given content before pushing to the API.
	 *
	 * @param  mixed $content 
	 * @return array          
	 */
	public function prepare($content)
	{
		if (blank($content)) {
			return [];
		}

		return call_user_func_array([RemoteAPI::transformer($this->name), '__invoke'], [$content, $this->client]);
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
			'base_uri' => "https://wger.de/api/v2/{$this->name}/",
			[
				'Authorization' => "Token {$secret}",
				'Accept' => 'application/json'
			]
		]);
	}
}