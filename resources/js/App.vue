<template>
    <VMenu />
    <div class="container-fluid mb-4">
        <router-view />
    </div>
    <Alert />
</template>

<script setup>
    import Alert from '@app/components/Alert.vue'
    import VMenu from '@app/components/VMenu.vue'
    import { useRoute } from 'vue-router'

    const route = useRoute()
</script>

<style scoped></style>
