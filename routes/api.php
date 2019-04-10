<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api')->namespace('Api')->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');

    Route::middleware(['jwt-auth', 'api-role:'.\App\Domain\Role\Role::ROLE_ADMIN])->group(function () {
        Route::get('teams', 'TeamController@getTeams');
        Route::post('team', 'TeamController@createTeam');
        Route::get('team/{id}', 'TeamController@getTeam');
        Route::post('team/{id}', 'TeamController@updateTeam');
        Route::post('delete-team', 'TeamController@deleteTeam');
    });

    Route::middleware(['jwt-auth', 'api-role:'.\App\Domain\Role\Role::ROLE_ADMIN.'|'.\App\Domain\Role\Role::ROLE_TEAM_OWNER])->group(function () {
        Route::get('users', 'UserController@getUsers');
        Route::post('user', 'UserController@createUser');
        Route::get('user/{id}', 'UserController@getUser');
        Route::post('user/{id}', 'UserController@updateUser');
        Route::post('delete-user', 'UserController@deleteUser');
    });
});
