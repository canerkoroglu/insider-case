<?php
namespace App\Http\Controllers;

use App\Services\FixtureService;
use Illuminate\Http\JsonResponse;

class FixtureController extends Controller
{
    public function generateFixture(FixtureService $fixtureService): JsonResponse
    {

        $fixture = $fixtureService->generateFixture();

        return response()->json(['created' => $fixture]);
    }
}
