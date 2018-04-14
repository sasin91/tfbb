<?php

namespace App\Spark;

use Laravel\Spark\Repositories\UserRepository;

class ExtendedUserRepository extends UserRepository
{
    /**
     * Load the relationships for the given user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function loadUserRelationships($user)
    {
        $user = parent::loadUserRelationships($user);

        $user->load('currentWorkout');

        return $user;
    }
}