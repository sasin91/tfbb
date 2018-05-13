<?php

namespace App;

use App\Concerns\RoutesUsingSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Laravel\Scout\Searchable;

class Meal extends Model
{
	use Searchable, RoutesUsingSlug;

	protected $fillable = [
		'name',
		'slug',
		'type',
		'description',
		'photo_url',
	];

	/**
	 * The URLs to this meal
	 * 
	 * @return \Illuminate\Support\Fluent
	 */
	public function getUrlsAttribute()
	{
		return new Fluent([
			'web' => url('meals', $this),
			'api' => [
				'foods' => [
					'store' => route('meals.foods.store', $this)
				],

				'show' => route('meals.show', $this),
				'update' => route('meals.update', $this),
				'destroy' => route('meals.destroy', $this)
			]
		]);
	}

	/**
	 * The total amount of carbs in the meal.
	 * 
	 * @return int
	 */
	public function getTotalCarbohydratesAttribute()
	{
		return $this->foods->sum('carbohydrate');
	}

	/**
	 * The total amount of energy in the meal.
	 * 
	 * @return int
	 */
	public function getTotalEnergyAttribute()
	{
		return $this->foods->sum('energy');
	}

	/**
	 * The total amount of fat in the meal.
	 * 
	 * @return int
	 */
	public function getTotalFatsAttribute()
	{
		return $this->foods->sum('fat');
	}

	/**
	 * The total amount of protein in the meal.
	 * 
	 * @return int
	 */
	public function getTotalProteinsAttribute()
	{
		return $this->foods->sum('protein');
	}

	/**
	 * The foods the meal consists of.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function foods()
	{
		return $this->belongsToMany(Food::class, 'meal_plans')->using(MealPlan::class);
	}

	/**
	 * The diets that offers this meal.
	 * 
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function diets()
	{
		return $this->belongsToMany(Diet::class, 'dishes')->using(Dish::class);
	}

	/**
     * Get the indexable data array for the model.
     *
     * @return array
     */
	public function toSearchableArray()
	{
		return [
            'name' => $this->name,
			'link' => $this->urls->web,
			'type' => $this->type,
			'banner_url' => $this->banner_url
        ];
	}
}
