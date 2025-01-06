<template>
    <div class="row mt-3 mb-4">
        <div class="col-sm-3">
            <div class="list-group">
                <router-link activeClass="active" :to="{ name: 'watchlists.show', params: { watchlist: watchlist.id } }" class="list-group-item" v-for="watchlist in watchlists">{{ watchlist.name }}</router-link>
            </div>
            <div class="mt-5">
                <div class="card">
                    <div class="card-header">Редактирование</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="watchlist-name" class="form-label">Название</label>
                            <input type="text" class="form-control" id="watchlist-name" v-model="watchlist.name">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-primary" @click="updateWatchlist">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
            <div class="row g-4 align-items-stretch" v-if="films.length && loaded">
                <div class="col-sm-4" v-for="film in films">
                    <FilmCard :film="film" />
                </div>
            </div>
            <div v-else-if="loaded" v-cloak class="alert alert-primary" role="alert">Список пуст</div>
            <Paginator class="mt-3" :currentPage="parseInt(currentPage)" :totalPages="totalPages" routeName="watchlists.show" :params="{ watchlist: route.params.watchlist }" />
        </div>
    </div>
</template>

<script setup>
    import { filmList } from '@app/api/films'
    import { watchlistList, watchlistShow, watchlistUpdate } from '@app/api/watchlists'
    import router from '@app/bootstrap/router'
    import { ref, watch } from 'vue'
    import { useRoute } from 'vue-router'

    const route = useRoute()

    const watchlists = ref([])
    const watchlist = ref({
        id: null,
        name: '',
    })
    const films = ref([])
    const loaded = ref(false)
    const currentPage = ref(route.query?.page || 1)
    const totalPages = ref(1)

    function loadWatchlists() {
        watchlistList().then(response => {
            watchlists.value = response.data
            checkLoadedWatchlists(response.data)
        })
    }

    function loadWatchlist() {
        if (route.params?.watchlist) {
            watchlistShow(route.params.watchlist).then(response => {
                watchlist.value = response.data
            })
        }
    }

    function loadFilms() {
        if (route.params.watchlist) {
            filmList({
                watchlist: route.params.watchlist,
                page: route.query?.page || 1,
            }).then(response => {
                films.value = response.data
                currentPage.value = response.meta.current_page
                totalPages.value = response.meta.last_page
            })
        } else {
            films.value = []
        }
        loaded.value = true
    }

    function updateWatchlist() {
        watchlistUpdate(watchlist.value.id, { name: watchlist.value.name }).then(response => {
            watchlist.value = response.data
            loadWatchlists()
        })
    }

    function checkLoadedWatchlists(data) {
        if (!route.params?.watchlist && data?.length) {
            router.push({ name: 'watchlists.show', params: { watchlist: data[0]?.id } })
        }
    }

    loadWatchlists()
    loadWatchlist()

    if (route.params.watchlist) {
        loadFilms()
    }

    watch(route, () => {
        loaded.value = false
        currentPage.value = route.query?.page
        checkLoadedWatchlists(watchlists.value)
        loadFilms()
        loadWatchlist()
    })
</script>

<style lang="scss" scoped></style>
