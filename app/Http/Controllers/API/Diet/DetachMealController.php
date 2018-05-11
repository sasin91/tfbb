<?php

namespace App\Http\Controllers\API\Diet;

use App\Diet;
use App\Http\Controllers\Controller;
use App\Meal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DetachMealController extends Controller
{
	public function destroy(Diet $diet)
	{
		$this->authorize('detachMeals', $diet);

		$meals = $this->validate(request(), [
			'meals' => 'required|array',
			'meals.*' => ['required', Rule::exists('dishes', 'meal_id')->where('diet_id', $diet->id)]
		])['meals'];

		$diet->meals()->detach($meals);
		$diet->searchable();
		
		return response()->json(['detached' => $meals]);
	}}
