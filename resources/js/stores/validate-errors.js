import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useValidateErrors = defineStore('validate-errors', () => {
    const validationErrors = ref({})

    function addError(field, error) {
        if (validationErrors.value[field]?.length) {
            validationErrors.value[field].push(error)
        } else {
            validationErrors.value[field] = [error]
        }
    }

    function setErrors(errors) {
        validationErrors.value = errors
    }

    function hasErrors(field) {
        return validationErrors.value?.[field]?.length
    }

    function getErrors(field) {
        return validationErrors.value?.[field]
    }

    function clearErrors() {
        validationErrors.value = {}
    }

    return { addError, setErrors, hasErrors, getErrors, clearErrors }
})
