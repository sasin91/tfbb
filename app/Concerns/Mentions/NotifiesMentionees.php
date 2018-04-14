<?php

namespace App\Concerns\Mentions;

use App\Jobs\NotifyMentioneesJob;

trait NotifiesMentionees
{	
	/**
	 * Boot the trait while the Model is booting.
	 * 	
	 * @return void
	 */
	public static function bootNotifiesMentionees()
	{
		static::saved(function ($model) {
			dispatch(new NotifyMentioneesJob($model));
		});
	}
}