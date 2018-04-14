<?php

namespace App\Inspections\Spam\Scanners;

use Illuminate\Support\Str;

class InvalidKeyword
{
	public function check($value)
	{
		foreach (config('spam.keywords') as $keyword) {
			if (Str::is($keyword, $value)) {
				return true;
			}
		}

		return false;
	}
}