<?php 

namespace App\Services;

use App\Services\RemoteAPIManager;
use Illuminate\Support\Facades\Facade;

class RemoteAPI extends Facade
{
	/**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
	protected static function getFacadeAccessor()
	{
		return RemoteAPIManager::class;
	}
}