<?php

namespace Tests\Unit;

use App\Domain\Team\Team;
use App\Domain\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTeamApiTest extends TestCase
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
    function it_get_team_from_id()
    {
        $team = factory(Team::class)->create();
        $response = $this->get('api/team/'.$team->id.'?token='.$this->authenticateUser());

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertEquals($team->id, $response->json('team.id'));
    }

    /** @test */
    function it_give_team_not_found_if_id_is_not_given()
    {
        $response = $this->get('api/team/abc?token='.$this->authenticateUser());

        $response->assertStatus(200)
            ->assertJson([
                'success' => false
            ]);
    }
}
