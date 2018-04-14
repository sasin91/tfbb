<?php

namespace App\Concerns;

use App\Comment;
use App\Jobs\ScrapCommentsJob;

trait Commentable
{	
	/**
	 * Scrap the comments of the commentable model, when deleted.
	 * 
	 * @return void
	 */
	public static function bootCommentable()
	{
		static::deleted(function($model) {
			dispatch(new ScrapCommentsJob($model));
		});
	}

	/**
	 * The comments on the model.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function comments()
	{
		return $this->morphMany(Comment::class, 'commentable');
	}
}