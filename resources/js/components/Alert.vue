<template>
    <div class="toast-container mb-3 me-3" v-show="store.hasAlerts()" style="z-index: 9999;">
        <div class="toast align-items-center show" :class="[`text-bg-${alert.type}`]" role="alert" aria-live="assertive" aria-atomic="true" v-for="(alert, index) in store.getAlerts()" :key="index">
            <div class="d-flex">
                <div class="toast-body">{{ alert.message }}</div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" @click="store.removeAlert(index)"></button>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useAlertsStore } from '@app/stores/alerts'

    const store = useAlertsStore()
</script>

<style scoped>
    .toast-container {
        position: fixed;
        bottom: 0;
        right: 0;
        z-index: 9999;
        max-width: 100vw;
    }

    .toast {
        max-width: 500px;
    }
</style>
