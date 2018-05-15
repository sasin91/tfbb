<?php

namespace App;

use App\Services\RemoteAPI;
use App\Services\USDA\NDB;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
	protected $fillable = [
		'name',
		'energy',
		'protein',
		'fat',
		'carbohydrate',
		'provider_name',
		'provider_id',
		'details'
	];

	protected $casts = ['details' => 'collection'];

	public static function fromNDB(array $result)
	{
		$carb = array_first($result['nutrients'], function ($nutrient) {
			return str_contains($nutrient['name'], 'Carbohydrate');
		});

		$energy = array_first($result['nutrients'], function ($nutrient) {
			return $nutrient['name'] == 'Energy';
		});

		$fat = array_first($result['nutrients'], function ($nutrient) {
			return $nutrient['name'] == 'Total lipid (fat)';
		});

		$protein = array_first($result['nutrients'], function ($nutrient) {
			return $nutrient['name'] == 'Protein';
		});

		return static::query()->create([
			'provider_name' => 'ndb',
			'provider_id' => $result['ndbno'],
			'name' => $result['name'],
			'carbohydrate' => "{$carb['value']}",
			'energy' => "{$energy['value']}",
			'fat' => "{$fat['value']}",
			'protein' => "{$protein['value']}",

			'details' => $result
		]);
	}

	/**
	 * Fetch the raw food report from NDB.
	 * 
	 * @return array
	 */
	public function details()
	{
		return RemoteAPI::driver($this->provider_name)->fetchReport($this->provider_id)['food'];
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
