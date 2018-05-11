<?php

namespace App\Http\Controllers\API\Diet;

use App\Diet;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DietFilesController extends Controller
{
	public function index(Diet $diet)
	{
		$this->authorize('view', $diet);

		return $diet->media->map->getUrl();
	}

	public function store(Diet $diet)
	{
		$this->authorize('uploadFiles', $diet);

    	$this->validate(request(), ['file' => 'required|file']);

    	return $diet
    		->addMedia(request('file'))
    		->toMediaCollection(
    			$this->mimeGroup(request('file'))
    		)->getUrl();

	}

	protected function mimeGroup($file)
	{
		if (starts_with($file->getMimeType(), 'image')) {
			return 'photos';
		}

		if (starts_with($file->getMimeType(), 'video')) {
			return 'videos';
		}

		if (in_array($file->getMimeType(), ['application/doc','application/pdf','application/docx'])) {
			return 'documents';
		}

		return 'default';
	}
}
