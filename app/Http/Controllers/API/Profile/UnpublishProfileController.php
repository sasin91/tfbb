<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class UnpublishProfileController extends Controller
{
	public function store(Profile $profile)
	{
		$this->authorize('unpublish', $profile);

		$profile->unpublish();

		return response(['published' => true]);
	}
}
