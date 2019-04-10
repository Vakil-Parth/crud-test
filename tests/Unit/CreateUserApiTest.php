<?php

namespace Tests\Unit;

use App\Domain\Role\Role;
use App\Domain\Team\Team;
use App\Domain\User\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class CreateUserApiTest extends TestCase
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

    public function validationDataProvider()
    {
        $faker = Factory::create();

        return [
            ['name is required.' => ['name' => '']],
            ['name must be a string.' => ['name' => $faker->randomDigit]],
            ['email is required.' => ['email' => '']],
            ['email must be a string.' => ['email' => $faker->randomDigit]],
            ['email must be a valid email.' => ['email' => $faker->word.'@.'.$faker->word]],
            ['password is required.' => ['password' => '']],
            ['password must be a string.' => ['password' => $faker->randomDigit]],
            ['password must be a minimum 6 digits.' => ['password' => 'abcde']],
            ['password must be a confirmed.' => ['password' => $faker->password, 'password_confirmation' => $faker->password]],
            ['team_id is required.' => ['team_id' => '']],
            ['role_id is required.' => ['role_id' => '']]
        ];
    }

    /** @test */
    function it_create_user()
    {
        $response = $this->post('api/user?token='.$this->authenticateUser(), $this->validFields());

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    /**
     * @dataProvider validationDataProvider
     */
    function test_it_validation($input)
    {
        $response = $this->post('api/user?token='.$this->authenticateUser(), $this->validFields($input));

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function validFields($overrides = [])
    {
        $role = Role::pluck('id')->toArray();
        $team = Team::pluck('id')->toArray();
        $password = $this->faker->password;

        return array_merge([
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'role_id' => $role[array_rand($role)],
            'team_id' => $team[array_rand($team)]
        ], $overrides);
    }

}
