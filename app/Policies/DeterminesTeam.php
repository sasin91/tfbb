<?php

namespace App\Policies;

use App\Team;

trait DeterminesTeam
{
	/**
	 * Determine if user is on team.
	 * 
	 * @param  \App\User $user 
	 * @param  string $team 
	 * @return boolean
	 */
	public function onTeam($user, $team)
	{
		return $user->onTeam(
			Team::where('name', $team)->first()
		);
	}
}