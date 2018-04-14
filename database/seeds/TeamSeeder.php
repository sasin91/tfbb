<?php

use App\User;
use Illuminate\Database\Seeder;
use Laravel\Spark\Contracts\Interactions\Settings\Teams\CreateTeam;
use Laravel\Spark\Spark;

class TeamSeeder extends Seeder
{
	protected $teams = [
		[
			'owner' => null,
			'name' => 'default',
			'slug' => 'default'
		],
	];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     	foreach ($this->teams as $team) {
     		$owner = $this->resolveUser(
     			array_pull($team, 'owner')
     		);

     		Spark::interact(CreateTeam::class, [$owner, $team]);   	
     	}   
    }

    private function resolveUser($user = null)
    {
        $user = $user ?: head(Spark::$developers);

    	return User::whereKey($user)
    		->orWhere('email', $user)
    		->orWhere('name', $user)
    		->firstOrFail();
    }
}
