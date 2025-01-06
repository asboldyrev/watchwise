<template>
    <div class="row mt-3 mb-4 g-4 align-items-stretch">
        <div class="col-sm-3" v-for="film in films">
            <FilmCard :film="film" />
        </div>
    </div>

    <Paginator :currentPage="parseInt(currentPage)" :totalPages="totalPages" routeName="films.list" />
</template>

<script setup>
    import { filmList } from '@app/api/films'
    import { ref, watch } from 'vue'
    import { useRoute } from 'vue-router'

    const route = useRoute()
    const films = ref([])
    const currentPage = ref(route.query?.page || 1)
    const totalPages = ref(1)

    function loadFilms() {
        filmList({ page: currentPage.value }).then(response => {
            films.value = response.data
            currentPage.value = response.meta.current_page
            totalPages.value = response.meta.last_page
        })
    }

    loadFilms()

    watch(route, (newRoute) => {
        currentPage.value = newRoute.query?.page
        loadFilms()
    })
</script>

<style lang="scss" scoped></style>
