<?php

namespace App\Inspections\Mentions;

use Scanners\Scanner;

class Mentions
{
	protected $scanners = [
		Scanners\AtUser::class
	];

	/**
	 * Determine if given value contains any mentions.
	 * 
	 * @param  string $value 
	 * @return boolean        
	 */
	public function check(string $value)
	{
		foreach ($this->scanners() as $scanner) {
			if ($scanner->check($value)) {
				return true;			
			}	
		}

		return false;	
	}

	/**
	 * Get the mentions in given value.
	 * 	
	 * @param  string $value 
	 * @return \Illuminate\Support\Collection        
	 */
	public function in(string $value)
	{
		return $this->scanners()->flatMap(function ($scanner) use ($value) {
			return $scanner->matches($value);
		});
	}

	/**
	 * Get the mention scanners
	 * 
	 * @return \Illuminate\Support\Collection
	 */
	public function scanners()
	{
		return collect($this->scanners)->map(function ($scanner) {
			return new $scanner;
		});
	}
}