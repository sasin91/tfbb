<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCurrentUserProfileRequest;
use App\Profile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CurrentUserProfileController extends Controller
{
	/**
	 * Display the current users profile
	 * 
	 * @param  \Illuminate\Http\Request $request 
	 * @return \Illuminate\Database\Eloquent\Model | null | \Illuminate\Http\Response        
	 */
	public function show(Request $request)
	{
		if ($request->wantsJson()) {
			return $request->user()->profile;
		}

		if (is_null($request->user()->profile)) {
			return redirect()->to('/settings#profile')->with('status', __('Please create your profile.'));
		}

		return view('profiles.show')->with('profile', $request->user()->profile->load('creator'));
	}
}
