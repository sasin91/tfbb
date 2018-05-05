<?php

namespace App\Services;

use App\Services\USDA\NDB;
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
	 * Create the USDA NDB driver.
	 * 
	 * @return \App\Services\USDA\NDB
	 */
	public function createNDBDriver()
	{
		return new NDB(
			config('services.ndb.key')
		);
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