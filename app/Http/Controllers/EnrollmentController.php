<?php

namespace App\Http\Controllers;

use App\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
	public function __construct()
	{
		$this->middleware('subscribed');
	}

	public function index()
	{
		return view('enrollments.enrolled.index')->withEnrolled(request()->user()->enrolled()->paginate());
	}

	public function show($id)
	{
		return view('enrollments.enrolled.show')->withEnrolled(Enrollment::with('enrollable')->findOrFail($id));
	}
}
