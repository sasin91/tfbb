<?php

namespace App\Http\Controllers\Auth\Social\Facebook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class FacebookLoginController extends Controller
{
    public function __invoke()
    {
    	return Socialite::driver('facebook')->redirect();
    }
}
