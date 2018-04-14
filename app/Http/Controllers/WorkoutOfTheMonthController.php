<?php

namespace App\Http\Controllers;

use App\WorkoutOfTheMonth;
use Illuminate\Http\Request;

class WorkoutOfTheMonthController extends Controller
{
    public function show(WorkoutOfTheMonth $workout)
    {
    	return view('workouts.show', ['workout' => $workout->current()]);
    }
}
