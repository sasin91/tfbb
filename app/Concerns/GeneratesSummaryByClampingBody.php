<?php

namespace App\Concerns;

use Illuminate\Support\Str;

trait GeneratesSummaryByClampingBody
{	
	public static function bootGeneratesSummaryByClampingBody()
	{
		static::saving(function ($workout) {
			if (blank($workout->summary)) {
				$workout->fill(['summary' => $workout->generateSummary()]);
			}
		});
	}

	public function generateSummary(int $limit = 60)
	{
		return Str::limit(strip_tags($this->body), $limit, '...');
	}
}