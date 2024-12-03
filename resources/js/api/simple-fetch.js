import { useAlertsStore } from "@app/stores/alerts"
import { useValidateErrors } from "@app/stores/validate-errors"

export default async function (uri, data) {
    const alertStore = useAlertsStore()
    const validateErrorsStore = useValidateErrors()

    return await fetch(uri, data).then(response => response.json()).then(response => {
        if (response?.error) {
            alertStore.addAlert('danger', response?.error)
            delete response.error
        }

        if (response?.message) {
            alertStore.addAlert('danger', response?.message)
            delete response.message
        }

        if (response?.errors) {
            validateErrorsStore.setErrors(response.errors)
            delete response.errors
        }

        if (response?.info) {
            alertStore.addAlert('info', response?.info)
            delete response.info
        }

        if (response?.success) {
            alertStore.addAlert('success', response?.success)
            delete response.success
        }

        if (response?.warning) {
            alertStore.addAlert('warning', response?.warning)
            delete response.warning
        }

        if (Object.keys(response).length == 0) {
            return ''
        }

        return response
    })
}
