<?php

namespace Tests\Integration\Auth\Social;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginWithoutPasswordTest extends TestCase
{
	use RefreshDatabase;

    /** @test */
    function cannot_login_without_a_password() 
    {
    	factory(\App\User::class)->create([
    		'email' => 'john@example.com',
    		'password' => null
    	]);

    	$this->json('POST', '/login', [
    		'email' => 'john@example.com',
    	])->assertJsonValidationErrors('password');

    	$this->json('POST', '/login', [
    		'email' => 'john@example.com',
    		'password' => null
    	])->assertJsonValidationErrors('password');

    	$this->json('POST', '/login', [
    		'email' => 'john@example.com',
    		'password' => 'null'
    	])->assertJsonValidationErrors('email');

    	$this->assertGuest();
    } 
}