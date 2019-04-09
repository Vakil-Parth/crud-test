<?php

namespace Tests\Unit;

use App\Domain\User\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTeamApiTest extends TestCase
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
    function it_create_team()
    {
        $response = $this->post('api/team?token='.$this->authenticateUser(), [
            'title' => $this->faker->word
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    /** @test */
    function it_require_title_field()
    {
        $response = $this->post('api/team?token='.$this->authenticateUser(), [
            'title' => ''
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false
            ]);
    }

    /** @test */
    function it_require_title_field_must_be_string()
    {
        $response = $this->post('api/team?token='.$this->authenticateUser(), [
            'title' => 2210
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false
            ]);
    }
}
