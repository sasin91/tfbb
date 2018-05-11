<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Dish extends Pivot
{
    protected $fillable = ['diet_id', 'meal_id'];

    public function diet() 
    {
    	return $this->belongsTo(Diet::class);
    }

    public function meal() 
    {
    	return $this->belongsTo(Meal::class);
    }
}
