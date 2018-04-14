<?php

namespace App\Http\Controllers\Auth\Social\Facebook;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class FacebookCallbackController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth.silent');
	}

    public function __invoke(Request $request)
    {
    	$socialUser = Socialite::driver('facebook')->user();

    	$user = $request->user() ?? User::firstOrNew(['facebook_id' => $socialUser->getId()]);

    	$user->forceFill([
            'name'     => $socialUser->getName(),
            'email'    => $socialUser->getEmail(),
            'photo_url' => $socialUser->getAvatar(),
            'facebook_id' => $socialUser->getId()
    	]);

    	$user->saveOrFail();

    	return redirect('/home');
    }
}
