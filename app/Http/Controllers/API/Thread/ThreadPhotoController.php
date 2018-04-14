<?php

namespace App\Http\Controllers\API\Thread;

use App\Http\Controllers\Controller;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ThreadPhotoController extends Controller
{
    public function store(Thread $thread)
    {
    	$this->authorize('uploadPhotos', $thread);

    	$this->validate(request(), [
    		'photo' => [
    			'required',
    			'image', 
    			Rule::dimensions()
    				->minWidth(320)
    				->minHeight(240)
    				->maxWidth(2160)
    				->maxHeight(4096)
    			]
    	]);

    	$media = $thread
            ->addMedia(request()->file('photo'))
            ->usingFileName(str_random())
            ->toMediaCollection('photos');

    	return response(['url' => $media->getFullUrl()], 201);
    }
}
