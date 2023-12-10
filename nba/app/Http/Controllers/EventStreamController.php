<?php

namespace App\Http\Controllers;

ini_set("output_buffering", "on");

use App\Models\NBAMatch;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventStreamController extends Controller
{
    /**
     * The stream source.
     *
     * @return StreamedResponse
     */
    public function stream(): StreamedResponse
    {
        return response()->stream(function () {
            while (true) {
                $scoreController = new NBAShootController();

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
                    $match->home_team_score = $scoreController->getScore($match->match_id, $match->home_team_id);
                    $match->away_team_score = $scoreController->getScore($match->match_id, $match->away_team_id);
                }

                echo "event: ping\n";
                $curDate = date(DATE_ISO8601);
                echo 'data:' . json_encode($matches);
                echo "\n\n";

                ob_flush();
                flush();

                // Break the loop if the client aborted the connection (closed the page)
                if (connection_aborted()) {
                    break;
                }
                usleep(50000); // 50ms
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }
}
