<template>
    <div class="row my-5">
        <div class="col-sm-4">
            <h1>
                {{ name }}
                <span v-if="film.name.ru && (film.name.ru || film.name.original)" class="h4 text-secondary">
                    ({{ film.name.en ? film.name.en : film.name.original }})
                </span>
            </h1>

            <div class="mb-4">
                <AgeLimit :film="film" />
                <img class="img-fluid" :src="film.images?.poster[0]?.media?.original_url">
            </div>
        </div>

        <div class="col-sm-8">
            //
        </div>
    </div>
</template>

<script setup>
    import { useRoute } from 'vue-router'
    import { filmShow } from '@app/api/films'
    import { computed, ref } from 'vue'

    import AgeLimit from '../components/AgeLimit.vue'

    const route = useRoute()
    const filmId = ref(route.params?.film)
    const film = ref({})

    filmShow(filmId.value).then(response => {
        if (response?.importing) {
            $echo
                .channel(`films.${filmId}`)
                .listen('.film.imported', loadFilm)
        } else {
            film.value = response.data
        }
    })

    function loadFilm() {
        filmShow(filmId.value).then(response => {
            film.value = response.data
        })
    }

    const name = computed(() => {
        if (film.value.name?.ru) {
            return film.value.name.ru
        }

        return film.value.name?.en ? film.value.name.en : film.value.name?.original
    })
</script>

<style lang="scss" scoped></style>
