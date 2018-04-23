<?php

namespace App\Concerns;

use App\Jobs\ScrapEnrollmentJob;
use App\Enrollment;

trait Enrollable
{
	/**
	 * Scrap the enrollments of the model, when deleted.
	 * 
	 * @return void
	 */
	public static function bootEnrollable()
	{
		static::deleted(function($model) {
			dispatch(new ScrapEnrollmentsJob($model));
		});
	}

	/**
	 * The most recent enroll of the current model.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\MorphOne
	 */
	public function latestEnrollment()
	{
		return $this->morphOne(Enrollment::class, 'enrollable')->latest();
	}

	/**
	 * All the models that has enrolled the current model.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\MorphMany
	 */
	public function enrollments()
	{
		return $this->morphMany(Enrollment::class, 'enrollable')->latest();
	}
}