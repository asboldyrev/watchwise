import { ref } from 'vue'
import { defineStore } from 'pinia'

import { userCurrent } from '@app/api/users'

export const useUserStore = defineStore('user', () => {
    const user = ref({})

    function get() {
        return user.value
    }

    function has() {
        return !!user.value.id
    }

    userCurrent().then(response => {
        user.value = response.data
    })

    return { get, has }
})
