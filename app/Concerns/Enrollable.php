<?php

namespace App\Concerns;

use App\Enrollment;
use App\Jobs\ScrapEnrollmentJob;
use Illuminate\Support\Facades\Auth;

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
	 * Enroll the current model
	 *
	 * @param \App\User|int|null $user
	 * @return \App\Enrollment
	 */
	public function enroll($user = null)
	{
		if (is_null($user)) {
			$user = Auth::id();
		}

		return $this->enrollments()->create([
			'user_id' => is_object($user) ? $user->id : $user
		]);
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