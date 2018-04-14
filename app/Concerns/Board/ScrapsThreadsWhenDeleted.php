<?php

namespace App\Concerns\Board;

use App\Jobs\Board\ScrapThreadsJob;

trait ScrapsThreadsWhenDeleted
{
	public static function bootScrapsThreadsWhenDeleted()
	{
    	static::deleted(function ($board) {
    		dispatch(new ScrapThreadsJob($board));
    	});
	}
}