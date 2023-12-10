<?php

namespace App\Services;

use App\Models\NBAPlayer;
use App\Models\NBATeam;
use App\Models\NBAMatch;
use Illuminate\Support\Collection;

class FixtureService
{
    private $teams;

    public function __construct()
    {
        // Retrieve all teams from the database
        $this->teams = NBATeam::from('teams')->get();
    }

    /**
     * Generate the fixture.
     */
    public function generateFixture(): true
    {
        $shuffleTeams = $this->teams->shuffle()->shuffle()->shuffle()->toArray();

        $homeTeams = array_slice($shuffleTeams, 0, 15);
        $awayTeams = array_slice($shuffleTeams, 15, 15);

        for ($k = 0; $k < 15; $k++) {
            $this->createMatch($homeTeams[$k]["team_id"], $awayTeams[$k]["team_id"])->toArray();
        }

        return true;
    }

    /**
     * Create a match between the given teams.
     *
     * @param int $homeTeamId
     * @param int $awayTeamId
     */
    protected function createMatch(int $homeTeamId, int $awayTeamId): NBAMatch
    {
        // Save the match to the database
        return NBAMatch::create([
            'home_team_id' => $homeTeamId,
            'away_team_id' => $awayTeamId,
            'started_at' => now(),
        ]);
    }
}
