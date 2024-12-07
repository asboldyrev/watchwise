<template>
    <div v-if="loaded" class="row my-5">
        <div class="col-sm-4">
            <h1>
                {{ name }}
                <span v-if="film.name?.ru && (film.name.ru || film.name.original)" class="h4 text-secondary">
                    ({{ film.name.en ? film.name.en : film.name.original }})
                </span>
            </h1>

            <div class="mb-4">
                <AgeLimit :film="film" />
                <img class="img-fluid" :src="film.images?.poster?.[0]?.media?.original_url">
            </div>
        </div>

        <div class="col-sm-8">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" :class="{ 'active': currentTab == 'main' }" @click="currentTab = 'main'" type="button" role="tab">Основное</button>
                </li>

                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#siquels-and-priquels" type="button" role="tab">Сиквелы и приквелы</button>
                </li> -->

                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#awards" type="button" role="tab">Награды</button>
                </li> -->

                <!-- <li class="nav-item" role="presentation">
					<button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#staff" type="button" role="tab">Состав</button>
				</li> -->

                <li class="nav-item" role="presentation" v-if="film.seasons && film.seasons.length">
                    <button class="nav-link" :class="{ 'active': currentTab == 'seasons' }" @click="currentTab = 'seasons'" type="button" role="tab">Сезоны</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade" :class="{ 'show active': currentTab == 'main' }">
                    <div class="mt-4">
                        <!-- @if ($film.getSing())
							<p><small class="border-secondary rounded-1 text-secondary border px-2 py-1">{{ $film.getSing() }}</small></p>
						@endif -->
                        <p><strong>Год</strong>: {{ film.year }}</p>
                        <p><strong>Длина</strong>: {{ film.length }} мин.</p>
                        <p>
                            <em>{{ film.slogan }}</em>
                        </p>
                        <p>{{ film.description }}</p>
                    </div>

                    <div class="mt-4">
                        <a class="btn btn-outline-secondary btn-sm" v-if="film.serial" :href="`https://www.kinopoisk.ru/series/${film.id}`" target="_blank">Кинопоиск</a>
                        <a class="btn btn-outline-secondary btn-sm" v-else :href="`https://www.kinopoisk.ru/film/${film.id}`" target="_blank">Кинопоиск</a>
                        <a v-if="film.imdb_id" class="btn btn-outline-secondary btn-sm ms-2" :href="`https://www.imdb.com/title/${film.imdb_id}`" target="_blank">IMDB</a>
                    </div>

                    <div class="mt-4">
                        <div>
                            <span v-for="country in film.countries" class="badge text-bg-secondary me-1">{{ country.name }}</span>
                        </div>

                        <div class="mt-2">
                            <span v-for="genre in film.genres" class="badge text-bg-secondary me-1">{{ genre.name }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card mt-4">
                                <div class="card-header">Оценки</div>
                                <ul class="list-group list-group-flush">
                                    <template v-for="(data, name) in film.rating">
                                        <li v-if="data.review" class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="me-auto ms-2">
                                                {{ name }}
                                                <span class="small" v-if="data.count">({{ data.count }})</span>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ data.review }}</span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>

                        <div class="col-sm-6" v-if="film.theaters?.length">
                            <div class="card mt-4">
                                <div class="card-header">Онлайн кинотеатры</div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item" v-for="theater in film.theaters">
                                        <a class="text-decoration-none mx-2" target="_blank" :href="theater.pivot.url">
                                            <img v-if="theater.images?.logo?.[0]?.urls?.origin" class="img-fluid me-2" width="30" :src="theater.images.logo[0].urls.origin" alt="">
                                            <span>{{ theater.name }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" :class="{ 'show active': currentTab == 'seasons' }">
                    <table class="table-hover table-striped mb-0 table">
                        <thead>
                            <tr>
                                <!-- <th>Сезон</th> -->
                                <th>Серия</th>
                                <th>Название</th>
                                <th>Релиз</th>
                                <th>Синопсис</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="season in film.seasons">
                                <tr>
                                    <th colspan="4">Сезон {{ season.number }}</th>
                                </tr>
                                <tr v-for="episode in season.episodes">
                                    <!-- <td>{{ season.number }}</td> -->
                                    <td>{{ episode.episode_number }}</td>
                                    <td>{{ episode.name.ru ? episode.name.ru : episode.name.en }}</td>
                                    <td class="text-end">{{ dayjs(episode.release_date).format('DD MMMM YYYY') }}</td>
                                    <td>{{ episode.synopsis }}</td>
                                </tr>
                            </template>
                            <!-- @foreach ($film->seasons()->orderBy('number')->get() as $season)
                            @foreach ($season->episodes()->orderBy('episode_number')->get() as $episode)

                            @endforeach
                            @endforeach -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { useRoute } from 'vue-router'
    import { filmShow } from '@app/api/films'
    import { computed, ref } from 'vue'
    import dayjs from '@app/bootstrap/dayjs'

    import AgeLimit from '../components/AgeLimit.vue'

    const route = useRoute()
    const filmId = ref(route.params?.film)
    const film = ref({})
    const currentTab = ref('main')
    const loaded = ref(false)

    filmShow(filmId.value).then(response => {
        if (response?.importing) {
            $echo
                .channel(`films.${filmId.value}`)
                .listen('.film.imported', loadFilm)
        } else {
            film.value = response.data
            loaded.value = true
        }
    })

    function loadFilm() {
        filmShow(filmId.value).then(response => {
            film.value = response.data
            loaded.value = true
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
