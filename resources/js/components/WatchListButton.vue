<template>
    <div class="dropdown d-inline-block">
        <button class="btn btn-outline-primary dropdown-toggle position-relative" :class="{ 'show': opened }" @click="opened = !opened" type="button" id="listDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Добавить в список
            <span v-if="hasAdded" class="position-absolute top-0 start-0 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
        </button>
        <ul class="dropdown-menu p-3" :class="{ 'show': opened }" aria-labelledby="listDropdown">
            <li v-for="watchlist in watchlists">
                <div class="form-check">
                    <input class="form-check-input" :disabled="disabled" type="checkbox" @change="changeWatchlist($event, watchlist)" :id="`watchlist-${watchlist.id}`" :checked="watchlist.film_added">
                    <label class="form-check-label" :for="`watchlist-${watchlist.id}`">{{ watchlist.name }}</label>
                </div>
            </li>
            <li v-if="watchlists?.length">
                <hr class="dropdown-divider">
            </li>
            <li>
                <label for="new-watchlist-name" class="form-label">Новый список</label>
                <div class="input-group">
                    <input type="text" :disabled="disabled" @keyup.enter="store" class="form-control" v-model="newWatchlistName" id="new-watchlist-name">
                    <button class="btn btn-outline-secondary" :disabled="disabled" @click="store" type="button">Добавить</button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
    import { computed, ref } from 'vue'
    import { watchlistList, watchlistStore } from '@app/api/watchlists'
    import { userWatchlistsUpdate } from '@app/api/users'

    const props = defineProps({
        filmId: {
            type: [Number, String],
            required: true
        }
    })

    const opened = ref(false)
    const watchlists = ref([])
    const newWatchlistName = ref('')
    const disabled = ref(false)

    // Костыль для закрытия дропдауна
    document.addEventListener('click', event => {
        if (!event.target.closest('.dropdown')) {
            opened.value = false
        }
    })

    function store() {
        if (newWatchlistName.value) {
            disabled.value = true
            watchlistStore({
                film_id: props.filmId,
                name: newWatchlistName.value
            }).then(response => {
                if (response?.data) {
                    watchlists.value = response.data
                }

                newWatchlistName.value = ''
                disabled.value = false
            })
        }
    }

    function changeWatchlist(event, watchlist) {
        disabled.value = true
        userWatchlistsUpdate({
            film_id: props.filmId,
            watchlist_id: watchlist.id,
            status: event.target.checked
        }).then(response => {
            if (response?.data) {
                watchlists.value = response.data
            }
            disabled.value = false
        })
    }

    const hasAdded = computed(() => {
        return watchlists.value.some(watchlist => watchlist.film_added)
    })

    disabled.value = true
    watchlistList({ film_id: props.filmId }).then(response => {
        if (response?.data) {
            watchlists.value = response.data
        }
        disabled.value = false
    })

</script>

<style lang="scss" scoped>
    .dropdown-menu {
        min-width: 300px;
    }
</style>
