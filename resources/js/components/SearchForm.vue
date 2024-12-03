<template>
    <div class="d-flex" role="search">
        <input class="form-control me-2" min="2" type="search" v-model="query" placeholder="Титаник" aria-label="Search">
        <router-link class="btn btn-outline-success" :to="{ name: 'search', query: { query } }">Найти</router-link>
    </div>

</template>

<script setup>
    import { ref, watch } from 'vue'
    import { useRoute } from 'vue-router'

    const route = useRoute()
    const query = ref(route.query?.query)

    watch(route, (newQuery) => {
        query.value = newQuery.query?.query
    })
</script>

<style lang="scss" scoped></style>
