<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class PublishProfileController extends Controller
{
	public function store(Profile $profile)
	{
		$this->authorize('publish', $profile);

		$profile->publish();

		return response(['published' => true]);
	}
}
