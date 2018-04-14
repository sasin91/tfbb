<?php

namespace App\Services;

class NullDriver
{
	public function resource(string $resource = null)
	{
		$resource = new Class {
			function prepare($content) {
				return $content;
			}
		};

		return optional($resource);
	}

	public function addTransformer(string $resource, $callable)
	{
		//
	}

	public function transformer(string $forResource)
	{
		return function ($content, $client) {
			return $content;
		};
	}
}