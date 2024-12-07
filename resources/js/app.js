import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from '@app/bootstrap/router'

import '@app/bootstrap/echo'

import App from '@app/App.vue'
import AgeLimit from '@app/components/AgeLimit.vue'

const pinia = createPinia()
const app = createApp(App)

app.component('AgeLimit', AgeLimit)

app.use(pinia)
app.use(router)
app.mount('#app')
