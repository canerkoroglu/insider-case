import { createApp } from 'vue'
import Matches from './components/Mathces.vue'

const app = createApp()

app.component('Matches', Matches)

app.mount('#app')
