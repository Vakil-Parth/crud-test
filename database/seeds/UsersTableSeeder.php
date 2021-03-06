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
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password')
        ]);

        $user->assignRole(Role::ROLE_ADMIN);
    }
}
