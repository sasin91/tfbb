<?php

namespace App\Inspections\Mentions\Scanners;

use App\User;

class AtUser implements Scanner
{
	public function check($value)
	{
		if (str_contains($value, '@')) {
			$mentionees = $this->candidates($value);

			return $this->users($mentionees)->exists();
		}

		return false;
	}

	public function matches($value)
	{
		$mentionees = $this->candidates($value);
		
		return $this->users($mentionees)->get();
	}

	protected function users($candidates)
	{
		return User::whereIn('nickname', $candidates)->orWhereIn('name', $candidates);
	}

	protected function candidates($value)
	{
		$candidates = explode('@', $value);
		array_shift($candidates);

		return array_map(function ($candidate) {
			return preg_replace("~[^_a-zA-Z0-9-]+~", "", $candidate);
		}, $candidates);
	}
}
