<?php

namespace App\Inspections\Spam;

class Spam
{
	protected $scanners = [
		Scanners\KeyHeldDown::class,
		Scanners\InvalidKeyword::class,
	];

	public function check($value)
	{
		foreach ($this->scanners as $scanner) {
			if ((new $scanner)->check($value)) {
				return true;
			}
		}

		return false;
	}
}