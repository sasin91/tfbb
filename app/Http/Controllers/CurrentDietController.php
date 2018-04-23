<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Profile;
use App\Diet;
use Illuminate\Http\Request;

class CurrentDietController extends Controller
{
	public function __construct()
	{
		$this->middleware('subscribed');
	}

	public function show()
	{
		return request()->user()->currentDiet;
	}

	public function store()
	{
		$this->validate(request(), [
			'diet_id' => 'required|integer|exists:diets,id'
		]);

		request()->user()->enrollDiet($diet = Diet::find(request('diet_id')));

		if (request()->wantsJson()) {
			return response(['current_diet_id' => $diet->id], 201);
		} else {
			return redirect()->back()->with('status', "You're now following {$diet->title}.");
		}
	}
}
