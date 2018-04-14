<?php

namespace App\Inspections\Spam\Scanners;

class KeyHeldDown
{
	public function check($value)
	{
		return preg_match('/^[\w*? -]+$/', $value)
			&& preg_match('/(.)\\1{4,}/', $value);
	}
}