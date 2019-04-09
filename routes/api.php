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

    Route::middleware(['jwt-auth'])->group(function () {
        Route::get('teams', 'TeamController@getTeams');
        Route::post('team', 'TeamController@createTeam');
        Route::get('team/{id}', 'TeamController@getTeam');
        Route::post('team/{id}', 'TeamController@updateTeam');
        Route::post('delete-team', 'TeamController@deleteTeam');
    });
});
