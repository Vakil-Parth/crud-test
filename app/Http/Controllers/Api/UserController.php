<?php

namespace App\Http\Controllers\Api;

use App\Domain\Role\Role;
use App\Domain\User\ApiRequest\CreateUserRequest;
use App\Domain\User\ApiRequest\UpdateUserRequest;
use App\Domain\User\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * get User List with Team and Role
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers()
    {
        $users = User::with(['teams', 'roles'])->paginate(5);

        if ($users->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Users table is Empty.'
            ]);
        }

        return response()->json([
            'success' => true,
            'users' => $users
        ]);
    }

    /**
     * Create User Api
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(CreateUserRequest $request)
    {
        $authUser = auth()->user();

        if ($authUser->hasRole(Role::ROLE_TEAM_OWNER)) {
            $teams = $authUser->load('teams')->teams->pluck('id')->toArray();
            $role = Role::find($request->get('role_id'));

            if (!(in_array($request->get('team_id'), $teams) && $role->name != Role::ROLE_TEAM_OWNER)) {

                return response()->json([
                    'success' => false,
                    'message' => 'You are not belongs to this Team.'
                ]);
            }
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        $user->roles()->sync([$request->get('role_id') => ['team_id' => $request->get('team_id')]], false);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.'
        ]);
    }

    /**
     * get User from id with included team and role
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($id)
    {
        $user = User::with(['teams', 'roles'])->find($id);

        if (!$user)
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Update user Api
     *
     * @param UpdateUserRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user)
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);

        $authUser = auth()->user();

        if ($authUser->hasRole(Role::ROLE_TEAM_OWNER)) {
            $teams = $authUser->load('teams')->teams->pluck('id')->toArray();
            $role = Role::find($request->get('role_id'));

            if (!(in_array($request->get('team_id'), $teams) && $role->name != Role::ROLE_TEAM_OWNER)) {

                return response()->json([
                    'success' => false,
                    'message' => 'You are not belongs to this Team.'
                ]);
            }
        }

        $user->name = $request->get('name');
        if ($request->get('password_confirmation')) {
            $request->password = bcrypt($request->get('password'));
        }
        $user->save();

        $user->roles()->sync([$request->get('role_id') => ['team_id' => $request->get('team_id')]]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.'
        ]);
    }

    /**
     * delete user api
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser(Request $request)
    {
        $user = User::find($request->get('user_id'));

        if (!$user)
            return response()->json([
                'success' => false,
                'message' => 'User not found.'
            ]);

        $user->roles()->detach();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
