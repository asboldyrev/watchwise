import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from '@app/bootstrap/router'

import '@app/bootstrap/echo'

import App from '@app/App.vue'
import AgeLimit from '@app/components/AgeLimit.vue'
import CountryFlag from '@app/components/CountryFlag.vue'
import Paginator from '@app/components/Paginator.vue'
import FilmCard from '@app/components/FilmCard.vue'

const pinia = createPinia()
const app = createApp(App)

app.component('AgeLimit', AgeLimit)
app.component('CountryFlag', CountryFlag)
app.component('Paginator', Paginator)
app.component('FilmCard', FilmCard)

app.use(pinia)
app.use(router)
app.mount('#app')
