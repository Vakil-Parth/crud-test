<?php

use Illuminate\Database\Seeder;
use App\Domain\Role\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesArray = [
            Role::ROLE_TEAM_OWNER,
            Role::ROLE_DESIGNER,
            Role::ROLE_DEVELOPER,
            Role::ROLE_TESTER
        ];

        foreach($rolesArray as $role) {
            Role::updateOrCreate(['name' => $role]);
        }
    }
}
