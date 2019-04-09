<?php

namespace Tests\Unit;

use App\Domain\Team\Team;
use App\Domain\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTeamListApiTest extends TestCase
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
    function it_list_teams()
    {
        $teams = factory(Team::class, 2)->create();

        $response = $this->get('api/teams?token='.$this->authenticateUser());

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonCount($teams->count(), 'teams.data');
    }

    /** @test */
    function it_gives_false_in_empty_list()
    {
        $response = $this->get('api/teams?token='.$this->authenticateUser());

        $response->assertStatus(200)
            ->assertJson([
                'success' => false
            ]);
    }
}
