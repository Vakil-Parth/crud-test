<?php

namespace Tests\Unit;

use App\Domain\Role\Role;
use App\Domain\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class DeleteUserApiTest extends TestCase
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
    function it_delete_user()
    {
        $user = factory(User::class)->create();

        $response = $this->post('api/delete-user?token='.$this->authenticateUser(), [
            'user_id' => $user->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    /** @test */
    function it_require_user_id_for_delete_user()
    {
        $response = $this->post('api/delete-user?token='.$this->authenticateUser(), [
            'user_id' => ''
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false
            ]);
    }
}
