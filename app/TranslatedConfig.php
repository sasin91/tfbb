<?php

namespace App;

class TranslatedConfig {
	public function get($key)
	{
		return $this->translate(config($key));
	}

	private function translate(array $items)
	{
		return collect($items)->map(function ($value, $key) {
			if (is_array($value)) {
				return $this->translate($value);
			}

			return __((string)$value);
		});
	}
}