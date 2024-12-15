<template>
    <div class="row mt-3 mb-4">
        <div class="col-sm-3" v-for="film in films">
            <FilmCard :film="film" />
        </div>
    </div>

    <nav v-if="totalPages > 1">
        <ul class="pagination">
            <li class="page-item" :class="{ 'disabled': currentPage == 1 }">
                <router-link :to="{ name: 'films.list', query: { page: undefined } }" class="page-link">«</router-link>
            </li>

            <li v-for="page in totalPages" class="page-item" :class="{ 'active': currentPage == page }" aria-current="page">
                <router-link :to="{ name: 'films.list', query: { page } }" class="page-link">{{ page }}</router-link>
            </li>

            <li class="page-item" :class="{ 'disabled': currentPage == totalPages }">
                <router-link class="page-link" :to="{ name: 'films.list', query: { page: totalPages } }">»</router-link>
            </li>
        </ul>
    </nav>
</template>

<script setup>
    import FilmCard from '@app/components/FilmCard.vue'

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
