<template>
    <h1 class="mt-4">Результат поиска по запросу «{{ query }}» <span class="text-secondary">{{ total }}</span></h1>

    <div class="row mt-3 mb-4">
        <div class="col-sm-3" v-for="film in films">
            <SearchFilmCard :film="film" />
        </div>
    </div>
    <!-- <span>{{ page }}</span> -->
</template>

<script setup>
    import SearchFilmCard from '@app/components/SearchFilmCard.vue'
    import { ref, watch } from 'vue'
    import { search } from '@app/api/search'
    import { useRoute } from 'vue-router'

    const route = useRoute()
    const query = ref(route.query?.query)

    const films = ref([])
    const total = ref()
    const page = ref(1)
    const lastPage = ref(1)

    function loadSearchResult() {
        search({ query: query.value }).then(response => {
            films.value = response.data.films
            total.value = response.data.total
            lastPage.value = response.data.last_page
        })
    }
    loadSearchResult()

    watch(route, (newQuery) => {
        query.value = newQuery.query?.query
        loadSearchResult()
    })
</script>

<style lang="scss" scoped></style>
