<?php

namespace App\Http\Controllers;

use App\Profile;
use Facades\App\Scores\Popularity;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
	public function index()
	{
		return view('profiles.index')->with(
			'profiles', 
			Profile::published()->latest('published_at')
				->with('creator')
				->paginate()
		);
	}

	public function show($id)
	{
		$profile = Profile::published()->with('creator')->findOrFail($id);

		Popularity::increment($profile);

		return view('profiles.show')->with('profile', $profile);
	}
}
