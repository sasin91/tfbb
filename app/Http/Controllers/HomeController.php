<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Guard;

class HomeController extends Controller
{
    public function show(Guard $guard)
    {
    	$user = $guard->user();

        return view('home')
            ->with('user', $user)
            ->with('enrollmentsCount', $user->enrollments()->count())
            ->with('workouts', $this->latestEnrolledWorkouts($user))
            ->with('diets', $this->latestEnrolledDiets($user));
    }

	 protected function latestEnrolledWorkouts($user)
	 {
	     return $user
	         ->enrollments()
	         ->latest()
	         ->take(5)
	         ->workouts()
	         ->with('enrollable')
	         ->get();
	 }

	 protected function latestEnrolledDiets($user)
	 {
	     return $user
	         ->enrollments()
	         ->latest()
	         ->take(5)
	         ->diets()
	         ->with('enrollable')
	         ->get();
	 }
}
