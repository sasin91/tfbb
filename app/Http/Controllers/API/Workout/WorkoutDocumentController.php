<?php

namespace App\Http\Controllers\API\Workout;

use App\Http\Controllers\Controller;
use App\Workout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkoutDocumentController extends Controller
{
    public function store(Workout $workout)
    {
    	$this->authorize('uploadDocuments', $workout);

    	$this->validate(request(), [
            'documents' => 'required|array',
    		'documents.*' => [
    			'required',
    			'mimes:doc,pdf,docx'
    		]
    	]);

    	$media = $workout
            ->addMediaFromRequest('documents')
            ->toMediaCollection('documents');

        return response([
        	'name' => $media->name,
        	'url' => $media->getUrl()
        ], 201);
    }
}
