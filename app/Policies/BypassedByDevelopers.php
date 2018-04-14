<?php

namespace App\Policies;

use Laravel\Spark\Spark;

trait BypassedByDevelopers
{
    /**
     * Bypass the policy when the User is a developer.
     *
     * @param \App\User $user
     * @param  string   $ability
     *
     * @return bool | null
     */
    public function before($user, $ability)
    {
        if (Spark::developer($user->email)) {
            return true;
        }
        return null;
    }
}