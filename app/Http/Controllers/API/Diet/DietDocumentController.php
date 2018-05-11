<?php

namespace App\Http\Controllers\API\Diet;

use App\Diet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DietDocumentController extends Controller
{
	public function store(Diet $diet)
	{
		$this->authorize('uploadDocuments', $diet);

    	$this->validate(request(), [
            'documents' => 'required|array',
    		'documents.*' => [
    			'required',
    			'mimes:doc,pdf,docx'
    		]
    	]);

    	$media = $diet
            ->addMediaFromRequest('documents')
            ->toMediaCollection('documents');

        return response([
        	'name' => $media->name,
        	'url' => $media->getUrl()
        ], 201);
	}
}
