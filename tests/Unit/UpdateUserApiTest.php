<?php

namespace Tests\Unit;

use App\Domain\Role\Role;
use App\Domain\Team\Team;
use App\Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class UpdateUserApiTest extends TestCase
{
   use WithFaker;
   use DatabaseTransactions;

    protected function authenticateUser()
    {
        $user = factory(User::class)->create();
        $user->assignRole(Role::ROLE_ADMIN);

        $token = \JWTAuth::fromUser($user);

        return $token;
    }

    /** @test */
    function it_update_team()
    {
        $role = Role::pluck('id')->toArray();
        $team = Team::pluck('id')->toArray();

        $user = factory(User::class)->create();

        $response = $this->post('api/user/'.$user->id.'?token='.$this->authenticateUser(), [
            'name' => $this->faker->word,
            'team_id' => $team[array_rand($team)],
            'role_id' => $role[array_rand($role)]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

}
