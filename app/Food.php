<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
	protected $fillable = [
		'name',
		'energy',
		'protein',
		'fat',
		'carbohydrate' 
	];

	public static function fromNDB(array $result)
	{
		$carb = array_first($result['nutrients'], function ($nutrient) {
			return str_contains($nutrient['name'], 'Carbohydrate');
		});

		$energy = array_first($result['nutrients'], function ($nutrient) {
			return $nutrient['name'] == 'Energy';
		});

		$fat = array_first($result['nutrients'], function ($nutrient) {
			return $nutrient['name'] = 'Total lipid (fat)';
		});

		$protein = array_first($result['nutrients'], function ($nutrient) {
			return $nutrient['name'] == 'Protein';
		});

		return new static([
			'name' => $result['name'],
			'carbohydrate' => "{$carb['value']} {$carb['unit']}",
			'energy' => "{$energy['value']} {$energy['unit']}",
			'fat' => "{$fat['value']} {$fat['unit']}",
			'protein' => "{$protein['value']} {$protein['unit']}"
		]);
	}

	/**
	 * The meals that contain the current food.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function meals()
	{
		return $this->belongsToMany(Meal::class, 'meal_plans')->using(MealPlan::class);
	}
}
