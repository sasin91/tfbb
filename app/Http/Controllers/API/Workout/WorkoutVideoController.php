<?php

namespace App\Http\Controllers\API\Workout;

use App\Http\Controllers\Controller;
use App\Workout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkoutVideoController extends Controller
{
    public function store(Workout $workout)
    {
    	$this->authorize('uploadVideos', $workout);

    	$this->validate(request(), [
            'videos' => 'required|array',
    		'videos.*' => [
    			'required',
    			'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/ogg,video/H264,video/H265'
            ]
    	]);

    	$media = $workout
            ->addMediaFromRequest('videos')
            ->usingFileName(str_random())
            ->toMediaCollection('videos');

    	return response(['url' => $media->getUrl()], 201);
    }
}
