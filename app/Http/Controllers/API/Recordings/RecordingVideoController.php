<?php

namespace App\Http\Controllers\API\Recordings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Recording;

class RecordingVideoController extends Controller
{
    public function store(Recording $recording)
    {
    	$this->authorize('uploadVideos', $recording);

    	$this->validate(request(), [
            'videos' => 'required|array',
    		'videos.*' => [
    			'required',
    			'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,video/ogg,video/H264,video/H265'
            ]
    	]);

    	$media = $recording
            ->addMediaFromRequest('videos')
            ->usingFileName(str_random())
            ->toMediaCollection('videos');

    	return response(['url' => $media->getUrl()], 201);
    }
}