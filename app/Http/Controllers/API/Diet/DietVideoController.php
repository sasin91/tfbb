<?php

namespace App\Http\Controllers\API\Diet;

use App\Diet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DietVideoController extends Controller
{
    public function store(Diet $diet)
    {
    	$this->authorize('uploadVideos', $diet);

    	$this->validate(request(), [
            'videos' => 'required|array',
    		'videos.*' => [
    			'required',
    			'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/ogg,video/H264,video/H265'
            ]
    	]);

    	$media = $diet
            ->addMediaFromRequest('videos')
            ->usingFileName(str_random())
            ->toMediaCollection('videos');

    	return response(['url' => $media->getUrl()], 201);
    }
}
