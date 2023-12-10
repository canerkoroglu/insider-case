<?php

namespace App\Http\Controllers;

use App\Models\NBAMatch;
use App\Models\NBAPlayer;
use App\Models\NBATeam;
use App\Models\NBAShoot;
use Illuminate\Http\Request;

set_time_limit(0);

class NBAShootController extends Controller
{
    public function shoot($matchId, $teamId, $playerId, $assistedBy = null, $quarter = null)
    {
        $match = NBAMatch::where("match_id", $matchId)->first();
        $team = NBATeam::where("team_id", $teamId)->first();
        $player = NBAPlayer::where("player_id", $playerId)->first();

        $shoot = new NBAShoot();

        $point = $this->randomPointGenerator();
        $success = $this->checkShootSuccess();

        return $shoot->create([
            'match_id' => $matchId,
            'team_id' => $teamId,
            'player_id' => $playerId,
            'assisted_by' => $assistedBy < 1 ? null : $assistedBy,
            'success' => $success,
            'point' => $point,
            'quarter' => $quarter,
        ]);
    }

    private function checkShootSuccess(): bool
    {
        $random = rand(0, 100);
        if ($random <= 60) {
            return true;
        } else {
            return false;
        }
    }

    private function randomPointGenerator(): int
    {
        $random = rand(0, 100);
        if ($random <= 70) {
            return 2;
        } else {
            return 3;
        }
    }

    public function getScore($matchId, $teamId)
    {
        $data = NBAShoot::where("match_id", $matchId)
            ->where('team_id', $teamId)
            ->where('success', true)
            ->sum('point');
        return $data;
    }

    public function getScoreByPlayer($matchId, $teamId, $playerId)
    {
        $data = NBAShoot::where("match_id", $matchId)
            ->where('team_id', $teamId)
            ->where('player_id', $playerId)
            ->where('success', true)
            ->sum('point');
        return $data;
    }

    public function totalAttackCountByTeam($matchId, $teamId)
    {
        $data = NBAShoot::where("match_id", $matchId)
            ->where('team_id', $teamId)
            ->count();
        return $data;
    }
}
