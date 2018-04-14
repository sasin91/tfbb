<?php

namespace App\Http\Controllers\API\Workout;

use App\Http\Controllers\Controller;
use App\Workout;
use App\WorkoutOfTheMonth;
use Illuminate\Http\Request;

class WorkoutOfTheMonthController extends Controller
{
    public function store(Workout $workout)
    {
    	$this->authorize('selectAsWorkoutOfTheMonth', $workout);

    	WorkoutOfTheMonth::select($workout);
    }
}