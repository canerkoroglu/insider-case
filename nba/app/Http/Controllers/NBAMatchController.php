<?php

namespace App\Http\Controllers;

use App\Models\NBAMatch;
use App\Models\NBAPlayer;
use App\Models\NBAEvent;
use App\Models\NBAShoot;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\NoReturn;
use Illuminate\Http\JsonResponse;

ini_set('max_execution_time', 300);
ini_set('set_time_limit', 300);

class NBAMatchController extends Controller
{
    public const MAX_ATTACK_TIME = 24;

    private $match;
    private int $matchId;
    private $homeTeamLineUp;
    private $awayTeamLineUp;
    private $attacker;
    private $defender;
    private array $playerList;
    private NBAEvent $event;
    private $fixture;
    private $shootController;

    public function __construct(Request $request)
    {
        //$this->matchId = $request->route('matchId');
        //$this->match = NBAMatch::where('match_id', $this->matchId)->first();

        $this->fixture = NBAMatch::from("matches")->get();
        $this->event = new NBAEvent();

        $this->shootController = new NBAShootController();
    }

    protected function assignMatchPlayers($teamId): Collection
    {
        $positions = ['PG', 'SG', 'SF', 'PF', 'C'];
        $players = collect();

        foreach ($positions as $k => $position) {
            $player = NBAPlayer::where('team_id', $teamId)
                ->where('position', ($k + 1))
                ->inRandomOrder()
                ->first();

            if ($player) {
                $players->push($player);
            }
        }
        return $players->shuffle();
    }

    #[NoReturn] private function createLineup(): void
    {
        $this->homeTeamLineUp = $this->setHomeTeamLineUp();
        $this->awayTeamLineUp = $this->setAwayTeamLineUp();
    }

    private function setHomeTeamLineUp(): Collection
    {
        return $this->playerList[$this->match->home_team_id] = $this->assignMatchPlayers($this->match->home_team_id);
    }

    private function setAwayTeamLineUp(): Collection
    {
        return $this->playerList[$this->match->away_team_id] = $this->assignMatchPlayers($this->match->away_team_id);
    }

    public function playAllMatches(): array
    {
        $fixture = $this->fixture;

        $newQuarter = null;

        $matches = [];
        $players = [];

        // create team meta data
        foreach ($fixture as $match) {
            $this->match = $match;
            $this->matchId = $match["match_id"];

            // create match meta data
            $this->createLineup();

            $this->match->status = 1;
            $this->match->home_players = $this->homeTeamLineUp;
            $this->match->away_players = $this->awayTeamLineUp;

            $players[$match["home_team_id"]] = $this->homeTeamLineUp;
            $players[$match["away_team_id"]] = $this->awayTeamLineUp;

            $this->event->create([
                'action' => 'players_created',
                'description' => 'Match -' . $this->matchId . '- players has been created.',
                'match_id' => $this->matchId
            ]);

            $this->jumpBall();

            $this->event->create([
                'action' => 'jump_ball',
                'description' => "Team id " . $this->attacker . " has been started the game.",
                'match_id' => $this->matchId
            ]);

            $this->match->attacker = $this->attacker;
            $this->match->defender = $this->defender;

            $matches[$match["match_id"]] = $this->match;
        }


        // Set the total number of requests
        $totalRequests = 48;
        // Set the time interval between requests in seconds
        $requestInterval = 5;
        // Set the total time limit for requests including code runtime in seconds
        $totalTimeLimit = 240;
        // Initialize the start time
        $startTime = microtime(true);

        $minAttackTime = 8;

        $this->event->create([
            'action' => 'started',
            'description' => "All matches has been started."
        ]);

        // simulate minutes
        for ($t = 1; $t <= $totalRequests; $t++) {
            $quarter = round(ceil($t / 12), 0);

            if ($t >= 40) {
                $minAttackTime = 6;
            }
            if ($t >= 44) {
                $minAttackTime = 4;
            }

            if ($newQuarter != $quarter && $quarter > 0) {
                $this->event->create([
                    'action' => 'quarter_started',
                    'description' => "--- " . $quarter . ". quarter has been started."
                ]);
                $newQuarter = $quarter;
            }

            foreach ($matches as $key => $match) {
                $this->match = $match;
                $this->matchId = $key;

                $this->attacker = $match->attacker;
                $this->defender = $match->defender;

                $allowedTeams = [$match->home_team_id, $match->away_team_id];

                if (!in_array($this->attacker, $allowedTeams) || !in_array($this->defender, $allowedTeams)) {
                    $this->invertAttack();
                }

                $clock = 60;
                // every loop equal to 1 minutes in real life

                while ($clock > 0) {
                    $attackTime = rand($minAttackTime, self::MAX_ATTACK_TIME);
                    $clock -= $attackTime;

                    $attackerPlayer = ($players[$this->attacker]->shuffle()->toArray())[0]["player_id"];
                    $assistedBy = $this->makeAssist($attackerPlayer, $players[$this->attacker]->toArray());

                    $this->shootController->shoot(
                        $this->matchId,
                        $this->attacker,
                        $attackerPlayer,
                        $assistedBy,
                        $quarter
                    );

                    // defender team can make turnover after attack
                    if ($this->invertAttack()) {
                        $tempAttacker = $this->attacker;
                        $this->attacker = $matches[$key]->attacker = $this->defender;
                        $this->defender = $matches[$key]->defender = $tempAttacker;
                    }
                }
            }

            $this->event->create([
                'action' => 'clock',
                'description' => "Playing " . $t . "'"
            ]);

            // Calculate the time elapsed
            $currentTime = microtime(true);
            $elapsedTime = $currentTime - $startTime;

            // Calculate the time remaining for the next request
            $timeRemaining = $requestInterval - ($elapsedTime % $requestInterval);

            // Wait for the remaining time before the next iteration
            if ($elapsedTime < $totalTimeLimit) {
                usleep($timeRemaining * 1e6);
            } else {
                // Break the loop if the total time limit is reached
                break;
            }
        }

        $this->event->create([
            'action' => 'finished',
            'description' => "All matches has been finished."
        ]);

        return [
            'message' => 'Matches finished',
            'results' => []
        ];
    }

    private function invertAttack(): bool
    {
        $random = rand(0, 100);
        if ($random <= 90) {
            return true;
        } else {
            return false;
        }
    }

    private function jumpBall(): void
    {
        $random = rand(0, 100);
        if ($random <= 50) {
            $this->attacker = $this->match->home_team_id;
            $this->defender = $this->match->away_team_id;
        } else {
            $this->attacker = $this->match->away_team_id;
            $this->defender = $this->match->home_team_id;
        }
    }

    private function makeAssist($playerId, $players): int|null
    {
        $random = rand(0, 100);
        if ($random <= 65) {
            // Use array_column to get the 'player_id' column
            $ids = array_column($players, 'player_id');

            // Use array_diff to remove the specified id
            $filteredIds = array_diff($ids, [$playerId]);

            // Use array_filter to keep only the elements with remaining ids
            $filteredPlayers = array_filter($players, function ($player) use ($filteredIds) {
                return in_array($player['player_id'], $filteredIds);
            });

            // Reset keys if needed
            $assistPlayer = array_rand(array_values($filteredPlayers));
            return $players[$assistPlayer]["player_id"];
        } else {
            return null;
        }
    }

    public function getMatches(): JsonResponse
    {
        $matches = NBAMatch::from("matches")
            ->join('teams as home_team', 'matches.home_team_id', '=', 'home_team.team_id')
            ->join('teams as away_team', 'matches.away_team_id', '=', 'away_team.team_id')
            ->select(
                'matches.*',
                'home_team.name as home_team_name',
                'home_team.short_name as home_team_short_name',
                'away_team.name as away_team_name',
                'away_team.short_name as away_team_short_name'
            )
            ->get();

        foreach ($matches as $match) {
            $match->home_team_score = $this->shootController->getScore($match->match_id, $match->home_team_id);
            $match->away_team_score = $this->shootController->getScore($match->match_id, $match->away_team_id);
            $match->home_team_attack = $this->shootController
                ->totalAttackCountByTeam($match->match_id, $match->home_team_id);
            $match->away_team_attack = $this->shootController
                ->totalAttackCountByTeam($match->match_id, $match->away_team_id);
        }

        $event = NBAEvent::from("events")->get()->last();

        $clock = "ongoing";

        if (isset($event->action) && $event->action == "finished") {
            $clock = "stopped";
        }

        return response()->json([
            "status" => true,
            "clock" => $clock,
            "event" =>
                [
                    "id" => $event->event_id ?? null,
                    "action" => $event->action ?? null,
                    "description" => $event->description ?? null,
                ],
            "matches" => $matches
        ]);
    }
}
