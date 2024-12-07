<template>
    <span v-if="isText" class="badge text-bg-secondary me-1">{{ country.name }}</span>
    <img v-else class="flag me-1" :src="`/img/flags/png-100/${country.iso2}.png`" :alt="country.name" :title="country.name">
</template>

<script setup>
    defineProps({
        country: {
            type: Object,
            required: true,
        },
        isText: {
            type: Boolean,
            default: false,
        }
    })
</script>

<style lang="scss" scoped>
    .flag {
        height: 20px;
        border: 1px solid #eee;
    }
</style>
