<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('app');
});

Route::get('/generate-fixture', 'App\Http\Controllers\FixtureController@generateFixture');

Route::get('/play-all-matches', 'App\Http\Controllers\NBAMatchController@playAllMatches');
Route::get('/matches', 'App\Http\Controllers\NBAMatchController@getMatches');

Route::get('/player-stats', 'App\Http\Controllers\NBAPlayerController@getPlayerStats');
Route::get('/player-stats-by-match/{{matchId}}', 'App\Http\Controllers\NBAPlayerController@getPlayerStats');
