<?php

namespace Tests\Integration\Auth\Social;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Two\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AuthorizingAgainstFacebookTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function visiting_login_redirects_to_facebook() 
	{
		$this->get('social/facebook')->assertRedirect();
	} 

	/** @test */
	function posting_to_callback_creates_a_user() 
	{
		$this->disableExceptionHandling();

		$socialUser = new SocialUser;
		$socialUser->name = 'John Doe';
		$socialUser->email = 'john@example.com';
		$socialUser->id = 'fake-facebook-id';

		Socialite::shouldReceive('driver->user')
			->once()
			->andReturn($socialUser);

		$this->post('social/facebook/callback')
			 ->assertRedirect('/home');

		$this->assertDatabaseHas('users', [
			'facebook_id' => 'fake-facebook-id',
			'name' => 'John Doe',
			'email' => 'john@example.com'
		]);
	} 
}
