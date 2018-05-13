<?php

namespace App\Http\Controllers\API\Meal;

use App\Http\Controllers\Controller;
use App\Meal;
use App\Services\RemoteAPI;
use Illuminate\Http\Request;

class MealFoodsController extends Controller
{
    public function store(Meal $meal)
    {
    	$this->authorize('attachFoods', $meal);

    	$this->validate(request(), [
    		'foods' => 'required|array',
    		'foods.*' => 'required_with:foods|string'
    	]);

    	$foods = collect(request('foods'))->map(function ($ndbno) {
	    	return RemoteAPI::driver('ndb')->food($ndbno);
	    });
        
    	return $meal->foods()->syncWithoutDetaching($foods->pluck('id'));
    }
}