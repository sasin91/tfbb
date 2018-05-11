<?php

namespace App\Http\Controllers\API\Diet;

use App\Diet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DietPhotoController extends Controller
{
	public function store(Diet $diet)
	{
    	$this->authorize('uploadPhotos', $diet);

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

    	$media = $diet
            ->addMediaFromRequest('photos')
            ->usingFileName(str_random())
            ->toMediaCollection('photos');

    	return response(['url' => $media->getUrl()], 201);
	}
}
