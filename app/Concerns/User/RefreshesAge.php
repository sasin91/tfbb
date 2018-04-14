<?php

namespace App\Concerns\User;

trait RefreshesAge
{
	public static function bootRefreshesAge()
	{
		static::saved(function ($profile) {
			$profile->refreshAgeIfNeeded();
		});
	}

	public function refreshAgeIfNeeded()
	{
		if ($this->shouldRefreshAge()) {
			$this->refreshAge();
		}

		return $this;
	}

	public function refreshAge()
	{
		return tap($this)->update([
			'age' => optional($this->born_at)->diffInYears(now())
		]);
	}

	public function shouldRefreshAge()
	{
		if ($this->born_at) {
			return $this->age !== $this->born_at->diffInYears(now());
		}

		return false;
	}
}