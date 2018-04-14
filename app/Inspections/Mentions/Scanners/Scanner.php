<?php

namespace App\Inspections\Mentions\Scanners;

interface Scanner
{	
	/**
	 * Determine if given value contains the current target.
	 * 
	 * @param  string $value 
	 * @return boolean        
	 */
	public function check($value);

	/**
	 * Extract the matches against the current target.
	 * 
	 * @param  string $value 
	 * @return array
	 */
	public function matches($value);
}