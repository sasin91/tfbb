<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class ProfileVideosController extends Controller
{
    public function store(Profile $profile)
    {
    	$this->authorize('uploadVideos', $profile);

    	$this->validate(request(), [
            'videos' => 'required|array',
    		'videos.*' => [
    			'required',
    			'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/ogg,video/H264,video/H265'
            ]
    	]);

    	$media = $profile
            ->addMediaFromRequest('videos')
            ->usingFileName(str_random())
            ->toMediaCollection('videos');

    	return response(['url' => $media->getUrl()], 201);
    }
}
