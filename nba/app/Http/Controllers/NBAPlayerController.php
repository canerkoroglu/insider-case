<?php

namespace App\Http\Controllers;

use App\Models\NBAPlayer;
use App\Models\NBAShoot;
use App\Models\NBATeam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NBAPlayerController extends Controller
{
    public function getPlayerStats($matchId = null): JsonResponse
    {
        $teams = NBATeam::all()->keyBy('team_id');

        $players = NBAPlayer::all()->map(function ($player) {
            $player['assist'] = 0;
            $player['two_points_score'] = 0;
            $player['two_points_attempt'] = 0;
            $player['two_points_success'] = 0;
            $player['three_points_score'] = 0;
            $player['three_points_attempt'] = 0;
            $player['three_points_success'] = 0;
            $player['match_id'] = null;
            $player['team_name'] = null;
            return $player;
        })->keyBy('player_id');

        if (null !== $matchId) {
            $players->where('match_id', $matchId);
        }

        $players = $players->toArray();

        $shoots = NBAShoot::all();

        foreach ($shoots as $shoot) {
            $player = $shoot->player_id;
            $point = $shoot->point;
            $success = $shoot->success;
            $assist = $shoot->assisted_by;
            $matchId = $shoot->match_id;

            $players[$player]['team_name'] = $teams[$shoot->team_id]['name'];

            if (null !== $assist) {
                $players[$assist]['assist'] += 1;
            }

            if ($point === 2) {
                $players[$player]['two_points_attempt'] += 1;
                if ($success == 1) {
                    $players[$player]['two_points_score'] += 2;
                    $players[$player]['two_points_success'] += 1;
                }
            }

            if ($point === 3) {
                $players[$player]['three_points_attempt'] += 1;
                if ($success == 1) {
                    $players[$player]['three_points_score'] += 3;
                    $players[$player]['three_points_success'] += 1;
                }
            }

            $players[$player]['match_id'] = $matchId;
        }

        // Filter out players whose match_id is null
        $players = array_filter($players, function ($player) {
            return $player['match_id'] !== null;
        });

        return response()->json([
            "status" => true,
            "players" => $players
        ]);
    }
}
