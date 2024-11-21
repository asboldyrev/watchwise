import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useAlertsStore = defineStore('alerts', () => {
    const alerts = ref([])

    function hasAlerts(type) {
        if (!type) {
            return alerts.value.length && true
        }

        for (const index in alerts.value) {
            if (alerts.value[index].type === type) {
                return true
            }
        }

        return false
    }

    function addAlert(type, message) {
        alerts.value.push({
            type,
            message,
        })
    }

    function getAlerts() {
        return alerts.value
    }

    function removeAlert(index) {
        alerts.value.splice(index, 1)
    }

    function forgetAlerts() {
        alerts.value = []
    }

    return { addAlert, getAlerts, removeAlert, forgetAlerts, hasAlerts }
})
