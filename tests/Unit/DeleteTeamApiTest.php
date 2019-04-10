<?php

namespace Tests\Unit;

use App\Domain\Role\Role;
use App\Domain\Team\Team;
use App\Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class DeleteTeamApiTest extends TestCase
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
    function it_delete_team()
    {
        $team = factory(Team::class)->create();

        $response = $this->post('api/delete-team?token='.$this->authenticateUser(), [
            'team_id' => $team->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('teams', $team->toArray());
    }

    /** @test */
    function it_require_team_id_for_delete_team()
    {
        $response = $this->post('api/delete-team?token='.$this->authenticateUser(), [
            'team_id' => ''
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false
            ]);
    }
}
