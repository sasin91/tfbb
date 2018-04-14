<?php

namespace App\Http\Controllers\API\Profile;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class LockProfileController extends Controller
{
	public function store(Profile $profile)
	{
		$this->authorize('lock', $profile);

		$profile->lock();

		return response(['locked' => true]);
	}
}
