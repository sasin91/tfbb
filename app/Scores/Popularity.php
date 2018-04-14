<?php

namespace App\Scores;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;

class Popularity
{	
	/**
	 * List the scores for given model.
	 * 	
	 * @param  \Illuminate\Database\Eloquent\Model | string $model 
	 * @param  integer $limit 
	 * @return \Illuminate\Support\Collection         
	 */
	public function list($model, $limit = -1)
	{
		return Collection::make(Redis::zrevrange($this->key($model), 0, $limit, 'WITHSCORES'))
			->map(function ($score, $item) {
				$item = json_decode($item, $asArray = true);
				
				$item['score'] = $score;

				return new Fluent($item);
			})->values();
	}

	/**
	 * Increment the score of a given model.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model | string $model
	 * @param  integer $amount 
	 * @return void
	 */
	public function increment($model, $amount = 1)
	{
		Redis::zincrby($this->key($model), $amount, $this->json($model));
	}

	/**
	 * Decrement the score of a given model.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model | string $model
	 * @param  integer $amount 
	 * @return void
	 */
	public function decrement($model, $amount = 1)
	{
		Redis::zincrby($this->key($model), (- abs($amount)), $this->json($model));
	}

	/**
	 * Forget all the scores of a given model.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model | string $model
	 * @return void
	 */
	public function forget($model)
	{
		Redis::del($this->key($model));
	}

	/**
	 * Get the popularity index for the given model.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model | string $model 
	 * @return string        
	 */
	public function key($model)
	{
		$key = (is_object($model) && method_exists($model, 'popularAs'))
			? $model->popularAs() 
			: 'popular_'.Str::lower(Str::plural(class_basename($model)));

		$enviroment = app()->environment();

		return "{$enviroment}_{$key}";
	}

	/**
	 * Get the values to store with the score.
	 * 
	 * @param  \Illuminate\Database\Eloquent\Model | string $model 
	 * @return string
	 */
	public function json($model)
	{
		if (method_exists($model, 'toPopularArray')) {
			return json_encode($model->toPopularArray());
		}

		return json_encode([
			'title' => $model->title,
			'link' => $model->link
		]);	
	}
}