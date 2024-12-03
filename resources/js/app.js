import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from '@app/bootstrap/router'

import '@app/bootstrap/echo'

import App from '@app/App.vue'

const pinia = createPinia()
const app = createApp(App)

app.use(pinia)
app.use(router)
app.mount('#app')
