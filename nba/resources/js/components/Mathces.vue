<script setup>
import axios from "axios";
import {ref, onMounted} from 'vue'

let intervalId = null

const generateFixture = async () => {
    try {
        const response = await axios.get('/generate-fixture');
        console.log(response.data);
    } catch (error) {
        console.error(error);
    }
};

const startMatches = async () => {
    try {
        const response = await axios.get('/play-all-matches');
        console.log(response.data);
    } catch (error) {
        console.error(error);
    }
};

let clockStatus;
let desc;
const matches = ref([])
const players = ref([])
const getMatches = () => {
    intervalId = setInterval(() => {

        axios.get('/matches')
            .then(res => {
                if (res.data.status) {
                    matches.value = res.data.matches;
                    desc = res.data.event.description;

                    console.log(desc);
                    clockStatus = true;

                    if (res.data.clock === "stopped") {
                        clearInterval(intervalId);
                        clockStatus = false;
                    }

                }
            })
            .catch(err => {
                console.log(err)
            })

        axios.get('/player-stats')
            .then(res => {
                if (res.data.status) {
                    players.value = res.data.players;
                }

            })
            .catch(err => {
                console.log(err)
            })

    }, 5000); // Fetch data every 5 seconds
}

onMounted(() => getMatches());
</script>
<template>
    <div class="flex flex-row">

        <div class="basis-3/12 text-left pl-4 pt-10 pr-20">

            <button
                v-on:click="generateFixture"
                class="align-middle w-full select-none font-sans font-bold font-sm text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-2 px-2 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                type="button">
                Generate Fixture
            </button>
            <br/><br/>
            <button
                v-on:click="startMatches"
                class="align-middle w-full select-none font-sans font-bold font-sm text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none text-xs py-2 px-2 rounded-lg bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                type="button">
                Start Matches
            </button>


        </div>

        <div class="basis-9/12 text-left pl-4">

            <div v-if="matches.length > 0">

                <table class="table-auto w-full">
                    <thead>
                    <tr>
                        <th colspan="3" class="bg-gray-800 text-white text-center">Home Team</th>
                        <th colspan="3" class="bg-gray-300 text-center">
                            <div v-if="clockStatus">{{ desc }}</div>
                            <div v-else>Finished</div>
                        </th>
                        <th colspan="3" class="bg-gray-800 text-white text-center">Away Team</th>
                    </tr>
                    <tr class="bg-gray-300">
                        <th></th>
                        <th class="text-center">Attack</th>
                        <th></th>
                        <th colspan="3" class="text-center">Score</th>
                        <th></th>
                        <th class="text-center">Attack</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="match in matches" :key="match.match_id">
                        <td class="text-center">
                            <button
                                class="relative clear-both align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                                type="button">
                            <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">{{
                                    match.home_team_short_name
                                }}</span>
                            </button>
                        </td>
                        <td class="text-center">{{ match.home_team_attack }}</td>
                        <td>{{ match.home_team_name }}</td>
                        <td>{{ match.home_team_score }}</td>
                        <td class="text-center">
                            -
                        </td>
                        <td class="text-right">{{ match.away_team_score }}</td>
                        <td class="text-right">{{ match.away_team_name }}</td>
                        <td class="text-center">{{ match.away_team_attack }}</td>
                        <td class="text-center">
                            <button
                                class="relative clear-both align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                                type="button">
                            <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">{{
                                    match.away_team_short_name
                                }}</span>
                            </button>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </div>
            <div v-else>
                <div class="w-auto w-full text-center pt-20">
                    <h1 class="text-xl font-black">Please generate matches of first week.</h1>
                </div>
            </div>

        </div>

    </div>

    <div v-if="matches.length > 0">
        <div class="flex flex-row">
            <div class="w-full">
                <table class="table-auto border w-full mt-10">
                    <thead>
                    <tr class="bg-amber-100">
                        <th class="text-center">Played</th>
                        <th class="text-center">Position</th>
                        <th class="text-center">Number</th>
                        <th class="text-left">Player Name</th>
                        <th>Team</th>
                        <th class="text-center">Assist</th>
                        <th class="text-center">2 pts</th>
                        <th class="text-center">2 pts Rate</th>
                        <th class="text-center">3 pts</th>
                        <th class="text-center">3 pts Rate</th>
                        <th class="text-center">Total pts.</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="player in players" :key="player.player_id">
                        <td class="text-center pt-2">{{ player.match_id ? 'O' : 'X' }}</td>
                        <td class="text-center pt-2">{{ player.position }}</td>
                        <td class="text-center pt-2"><button
                            class="relative clear-both align-middle select-none font-sans font-medium text-center uppercase transition-all disabled:opacity-50 disabled:shadow-none disabled:pointer-events-none w-10 max-w-[40px] h-10 max-h-[40px] rounded-lg text-xs bg-gray-900 text-white shadow-md shadow-gray-900/10 hover:shadow-lg hover:shadow-gray-900/20 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none"
                            type="button">
                            <span class="absolute transform -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">{{ player.jersey_number }}</span>
                        </button></td>
                        <td>{{ player.name }}</td>
                        <td>{{ player.team_name }}</td>
                        <td class="text-center">{{ player.assist }}</td>
                        <td class="text-center">{{ player.two_points_score }}</td>
                        <td class="text-center">{{ player.two_points_attempt>0 ? ((player.two_points_success / player.two_points_attempt)*100).toFixed(1) + '%' : '-' }}</td>
                        <td class="text-center">{{ player.three_points_score }}</td>
                        <td class="text-center">{{ player.three_points_attempt>0 ? ((player.three_points_success / player.three_points_attempt)*100).toFixed(1) + '%' : '-' }}</td>
                        <td class="text-center">{{ (player.two_points_score + player.three_points_score) ?? 0 }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</template>
