<?php

namespace App\Http\Controllers\API\Workout;

use App\Workout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkoutEnrollmentController extends Controller
{
    public function store(Workout $workout)
    {
        $this->authorize('enroll', $workout);

        return $workout->enroll();
    }
}
