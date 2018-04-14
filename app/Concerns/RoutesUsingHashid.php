<?php

namespace App\Concerns;

use Hashids\Hashids;
use Illuminate\Support\Str;

trait RoutesUsingHashid
{	
	/**
	 * Build a hashid if none exists when the model is being created.
	 * 
	 * @return void
	 */
	public static function bootRoutesUsingHashid()
	{
		static::created(function ($model) {
			$model->update(['hashid' => $model->hashid()]);
		});
	}

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
	{
		return 'hashid';
	}

	/**
	 * Build a hashid from the id key.
	 * 
	 * @return string
	 */
	public function hashid()
	{
		return $this->buildHashidUsing()->encode($this->getKey());
	}

	/**
	 * Get the hashids config key for the model.
	 * 
	 * @return string
	 */
	protected function hashidsConfigKey()
	{
		$name = Str::lower(class_basename($this));

		return "hashids.{$name}";
	}

	/**
	 * Get the Hashids instance for the model.
	 *  
	 * @return \Hashids\Hashids
	 */
	public function buildHashidUsing()
	{
		$config = config($this->hashidsConfigKey());

		return new Hashids(
			array_get($config, 'salt', config('hashids.default.salt')),
			array_get($config, 'length', config('hashids.default.length')), 
			array_get($config, 'alphabet', config('hashids.default.alphabet'))
		);
	}
}