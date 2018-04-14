<?php

namespace App\Http\Controllers\API\Workout;

use App\Http\Controllers\Controller;
use App\Workout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkoutPhotoController extends Controller
{
    public function store(Workout $workout)
    {
    	$this->authorize('uploadPhotos', $workout);

    	$this->validate(request(), [
            'photos' => 'required|array',
    		'photos.*' => [
    			'required',
    			'image', 
    			Rule::dimensions()
    				->minWidth(320)
    				->minHeight(240)
    				->maxWidth(2160)
    				->maxHeight(4096)
    			]
    	]);

    	$media = $workout
            ->addMediaFromRequest('photos')
            ->usingFileName(str_random())
            ->toMediaCollection('photos');

    	return response(['url' => $media->getUrl()], 201);
    }
}
