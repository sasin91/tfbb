<?php

namespace App\Concerns\Thread;

use Illuminate\Support\Str;

trait SummarizesBody
{
	public static function bootSummarizesBody()
	{
		static::saving(function ($thread) {
			if (blank($thread->summary)) {
				$thread->summary = Str::limit($thread->body, 50);
			}
		});
	}
}