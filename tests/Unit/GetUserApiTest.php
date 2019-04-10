<?php

namespace Tests\Unit;

use App\Domain\Role\Role;
use App\Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class GetUserApiTest extends TestCase
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
    function it_get_user_from_id()
    {
        $user = factory(User::class)->create();
        $response = $this->get('api/user/'.$user->id.'?token='.$this->authenticateUser());

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertEquals($user->id, $response->json('user.id'));
    }

    /** @test */
    function it_give_user_not_found_if_id_is_not_given()
    {
        $response = $this->get('api/user/abc?token='.$this->authenticateUser());

        $response->assertStatus(200)
            ->assertJson([
                'success' => false
            ]);
    }
}
