<?php

namespace App\Http\Controllers;

use App\Workout;
use Facades\App\Scores\Popularity;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function index()
    {
    	$this->authorize('index', new Workout);

    	return view('workouts.index')->with('workouts', Workout::all(['id', 'title', 'slug']));
    }

    public function show(Workout $workout)
    {
    	$this->authorize('view', $workout);

    	Popularity::increment($workout);

    	return view('workouts.show')->with('workout', $workout->load(['media', 'exercises']));
    }
}
