<?php

namespace App\Services;

use App\Services\Wger\Wger;
use Illuminate\Support\Manager;

class RemoteAPIManager extends Manager
{
	/**
     * Get the default driver name.
     *
     * @return string
     */
	public function getDefaultDriver()
	{
		return config('remoteAPI.driver');
	}

	/**
	 * Create a Null driver.
	 * 
	 * @return \App\Services\NullDriver
	 */
	public function createNullDriver()
	{
		return new NullDriver;
	}

	/**
	 * Create the Wger driver.
	 * 
	 * @return \App\Services\Wger\Wger
	 */
	public function createWgerDriver()
	{
        return new Wger(
            config('services.wger.secret')
        );
	}
}