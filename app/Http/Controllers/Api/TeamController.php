<?php

namespace App\Http\Controllers\Api;

use App\Domain\Team\Team;
use App\Domain\Team\ApiRequest\TeamRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * get All Teams
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeams()
    {
        $teams = Team::paginate(5);

        if ($teams->isEmpty())
            return response()->json([
                'success' => false,
                'message' => 'Teams are empty.'
            ]);

        return response()->json([
            'success' => true,
            'teams' => $teams
        ]);
    }

    /**
     * create Team
     *
     * @param TeamRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTeam(TeamRequest $request)
    {
        Team::create([
            'title' => $request->get('title')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Team created successfully.'
        ]);
    }

    /**
     * get Team from id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTeam($id)
    {
        $team = Team::find($id);

        if (!$team)
            return response()->json([
                'success' => false,
                'message' => 'Team not found.'
            ]);

        return response()->json([
            'success' => true,
            'team' => $team
        ]);
    }

    /**
     * update Team from id
     *
     * @param TeamRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTeam(TeamRequest $request, $id)
    {
        $team = Team::find($id);

        if (!$team)
            return response()->json([
                'success' => false,
                'message' => 'Team not found.'
            ]);

        $team->title = $request->get('title');
        $team->save();

        return response()->json([
            'success' => true,
            'message' => 'Team updated successfully.'
        ]);
    }

    /**
     * delete team
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteTeam(Request $request)
    {
        $team = Team::find($request->get('team_id'));

        if (!$team)
            return response()->json([
                'success' => false,
                'message' => 'Team not found.'
            ]);

        $team->users()->detach();
        $team->delete();

        return response()->json([
            'success' => true,
            'message' => 'Team deleted successfully.'
        ]);
    }
}
