<template>
    <div class="d-flex" role="search">
        <div class="input-group">
            <input class="form-control" @keyup.enter="search" min="2" type="search" v-model="query" placeholder="Титаник" aria-label="Search">
            <router-link class="btn btn-outline-secondary" :to="{ name: 'search', query: { query } }">
                <i class="ri-search-line"></i>
            </router-link>
        </div>

    </div>

</template>

<script setup>
    import { ref, watch } from 'vue'
    import { useRoute, useRouter } from 'vue-router'

    const route = useRoute()
    const router = useRouter()
    const query = ref(route.query?.query)

    function search() {
        router.push({ name: 'search', query: { query: query.value } })
    }

    watch(route, (newQuery) => {
        query.value = newQuery.query?.query
    })
</script>

<style lang="scss" scoped></style>
