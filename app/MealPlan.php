<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MealPlan extends Pivot
{
	protected $fillable = [
		'meal_id', 'food_id' 
	];

	public function meal() 
	{
		return $this->belongsTo(Meal::class);
	}

	public function food() 
	{
		return $this->belongsTo(Food::class);
	}
}
