<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use App\Workout;
use Illuminate\Http\Request;

class CurrentWorkoutController extends Controller
{
	public function __construct()
	{
		$this->middleware('subscribed');
	}

	public function store()
	{
		$this->validate(request(), [
			'workout_id' => 'required|integer|exists:workouts,id'
		]);

		request()->user()->currentWorkout()->associate(
			$workout = Workout::find(request('workout_id'))
		)->saveOrFail();

		if (request()->wantsJson()) {
			return response(['current_workout_id' => $workout->id], 201);
		} else {
			return redirect()->back()->with('status', "You're now following {$workout->title}.");
		}
	}
}
