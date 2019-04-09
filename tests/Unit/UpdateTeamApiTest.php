<?php

namespace Tests\Unit;

use App\Domain\Team\Team;
use App\Domain\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTeamApiTest extends TestCase
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
    function it_update_team()
    {
        $team = factory(Team::class)->create();

        $response = $this->post('api/team/'.$team->id.'?token='.$this->authenticateUser(), [
            'title' => $this->faker->word
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    /** @test */
    function it_require_title_field_on_update_team()
    {
        $team = factory(Team::class)->create();

        $response = $this->post('api/team/'.$team->id.'?token='.$this->authenticateUser(), [
            'title' => ''
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false
            ]);
    }

    /** @test */
    function it_require_title_field_must_be_string_on_update_team()
    {
        $team = factory(Team::class)->create();

        $response = $this->post('api/team/'.$team->id.'?token='.$this->authenticateUser(), [
            'title' => 2210
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false
            ]);
    }
}
