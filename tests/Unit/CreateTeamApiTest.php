<?php

namespace Tests\Unit;

use App\Domain\Role\Role;
use App\Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CreateTeamApiTest extends TestCase
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
