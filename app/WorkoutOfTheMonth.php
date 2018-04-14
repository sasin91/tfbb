<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;

class WorkoutOfTheMonth
{
	public static function select(Workout $workout, $date = null)
	{
		$date = static::castToDateTime($date);

		Redis::set(static::key($date), json_encode([
			'month' => $date->format('m'),
			'id' => $workout->id
		]));
	}

	public static function current()
	{
		return static::at(now());
	}

	public static function at($date)
	{
		$date = static::castToDateTime($date);

		$item = static::forYear($date)->where('month', $date->format('m'))->first();

		if (is_null($item)) {
			return null;
		}

		return Workout::with('exercises')->findOrFail($item->id);
	}

	public static function all($date = null)
	{
		return static::forYear($date)->mapToDictionary(function ($item) {
			return [$item->month => Workout::find($item->id)];
		})->filter();
	}

	public static function forYear($date = null)
	{
		$date = static::castToDateTime($date);

		return collect(Redis::get(static::key($date)))->map('json_decode');	
	}

	public static function castToDateTime($date)
	{
		if ($date instanceof \DateTime) {
			return $date;
		}

		return $date ? Carbon::parse($date) : Carbon::now();
	}

	public static function key($date = null)
	{
		$date = static::castToDateTime($date);

		return app()->environment().'_workout-of-the-month.'.$date->format('Y');
	}
}