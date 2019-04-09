<?php

use Illuminate\Database\Seeder;
use App\Domain\User\User;
use App\Domain\Role\Role;
use App\Domain\Team\Team;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create();

        $role = Role::where('name', Role::ROLE_TEAM_OWNER)->first();

        $team = Team::first();

        $user->roles()->sync([$role->id => ['team_id' => $team->id]], false);
    }
}
