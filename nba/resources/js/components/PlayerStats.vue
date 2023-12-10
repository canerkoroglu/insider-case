<script setup>
    import axios from "axios";
    import {ref, onMounted} from 'vue'

    const players = ref([])
    const getPlayerStats = () => {
        axios.get('/player-stats')
            .then(res => {
                players.value = res.data
            })
            .catch(err => {
                console.log(err)
            })
    }

    onMounted(() => getPlayerStats());
</script>
<template>
    <h1>Player Stats</h1>
</template>
