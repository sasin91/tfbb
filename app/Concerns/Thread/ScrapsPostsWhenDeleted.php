<?php

namespace App\Concerns\Thread;

use App\Jobs\Thread\ScrapPostsJob;

trait ScrapsPostsWhenDeleted
{
	public static function bootScrapsPostsWhenDeleted()
	{
		static::deleted(function ($thread) {
			dispatch(new ScrapPostsJob($thread));
		});
	}
}