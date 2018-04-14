<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class UnlockProfileController extends Controller
{
	public function store(Profile $profile)
	{
		$this->authorize('unlock', $profile);

		$profile->unlock();

		return response(['locked' => false]);
	}
}
