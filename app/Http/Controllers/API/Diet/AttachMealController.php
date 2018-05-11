<?php

namespace App\Http\Controllers\API\Diet;

use App\Diet;
use App\Http\Controllers\Controller;
use App\Meal;
use Illuminate\Http\Request;

class AttachMealController extends Controller
{
	public function store(Diet $diet)
	{
		$this->authorize('attachMeals', $diet);
		
		$meals = $this->validate(request(), [
			'meals' => 'required|array',
			'meals.*' => 'required|exists:meals,id'
		])['meals'];

		$diet->meals()->attach($meals);
		$diet->searchable();
		
		return response()->json(['attached' => $meals]);
	}
}
