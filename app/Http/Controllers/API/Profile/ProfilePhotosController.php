<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfilePhotosController extends Controller
{
    public function store(Profile $profile)
    {
    	$this->authorize('uploadPhotos', $profile);

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

    	$media = $profile
            ->addMediaFromRequest('photos')
            ->usingFileName(str_random())
            ->toMediaCollection('photos');

    	return response(['url' => $media->getUrl()], 201);
    }
}
