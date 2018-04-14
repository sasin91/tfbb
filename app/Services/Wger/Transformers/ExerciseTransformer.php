<?php

namespace App\Services\Wger\Transformers;

use App\Services\RemoteAPI;

class ExerciseTransformer 
{	
	/**
	 * Transform the given model to a digestable format for Wger API.
	 * 
	 * @param  \App\Exercise $model  
	 * @param  \GuzzleHttp\Client $client 
	 * @return array         
	 */
	public function __invoke($model, $client)
	{
		return [
			'description' => $model->description,
			'name' => $model->name,
			'category' => array_first($model->muscles),
			'muscles' => $this->resolveMuscleIds($model->muscles),
			'equipment' => $this->resolveEquiptmentIds($model->equipment)
		];
	}

	public function resolveMuscleIds($muscles)
	{
		return array_map(function ($muscle) {
			if (is_numeric($muscle)) {
				return $muscle;
			}

			$response = RemoteAPI::resource('exercisecategory')->get();

			$categories = json_decode($response->getBody()->getContents(), true)['results'];

			$category = array_first($categories, function ($category) use ($muscle) {
				return $category['name'] == $muscle;
			});

			return array_get($category, 'id');
		}, array_wrap($muscles));
	}

	public function resolveEquiptmentIds($equipment)
	{
		return array_map(function ($item) {
			if (is_numeric($item)) {
				return $item;
			}

			$response = RemoteAPI::resource('equipment')->get();

			$equipment = json_decode($response->getBody()->getContents(), true)['results'];

			$equipment = array_first($equipment, function ($equipment) use ($item) {
				return $equipment['name'] == $item;
			});

			return array_get($equipment, 'id');
		}, array_wrap($equipment));
	}
}