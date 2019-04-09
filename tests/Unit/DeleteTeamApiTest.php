<?php

namespace Tests\Unit;

use App\Domain\Team\Team;
use App\Domain\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTeamApiTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    protected function authenticateUser()
    {
        $user = factory(User::class)->create();

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
