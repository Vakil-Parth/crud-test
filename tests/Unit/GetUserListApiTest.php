<?php

namespace Tests\Unit;

use App\Domain\Role\Role;
use App\Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class GetUserListApiTest extends TestCase
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
    function it_list_users()
    {
        factory(User::class, 2)->create();

        $response = $this->get('api/users?token='.$this->authenticateUser());

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }
}
