<template>
    <div v-if="loaded" class="row my-2">
        <div class="col-sm-3">
            <div class="mb-4">
                <AgeLimit :film="film" />
                <img class="poster rounded img-fluid" :src="film.images?.poster?.[0]?.media?.original_url">
            </div>
        </div>

        <div class="col-sm-9">
            <h1>
                {{ name }}
                <span v-if="film.serial && film.start_year == film.end_year">({{ film.start_year }})</span>
                <span v-else-if="film.serial">({{ film.start_year }} - {{ film.end_year ? film.end_year : '...' }})</span>
                <span v-else>({{ film.year }})</span>
            </h1>
            <h3 v-if="film.name?.ru && (film.name.ru || film.name.original)" class="h4 text-secondary">
                {{ film.name.en ? film.name.en : film.name.original }}
            </h3>

            <ul class="nav nav-tabs mt-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" :class="{ 'active': currentTab == 'main' }" @click="currentTab = 'main'" type="button" role="tab">Основное</button>
                </li>

                <li class="nav-item" role="presentation" v-if="film?.awards?.length">
                    <button class="nav-link" :class="{ 'active': currentTab == 'awards' }" @click="currentTab = 'awards'" type="button" role="tab">Награды</button>
                </li>

                <li class="nav-item" role="presentation" v-if="film.persons.length">
                    <button class="nav-link" :class="{ 'active': currentTab == 'staff' }" @click="currentTab = 'staff'; currentProfession = 'WRITER'" type="button" role="tab">Состав</button>
                </li>

                <li class="nav-item" role="presentation" v-if="film.seasons && film.seasons.length">
                    <button class="nav-link" :class="{ 'active': currentTab == 'seasons' }" @click="currentTab = 'seasons'" type="button" role="tab">Сезоны</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade" :class="{ 'show active': currentTab == 'main' }">
                    <div class="row mt-4">
                        <div class="col-sm-8">
                            <!-- @if ($film.getSing())
							<p><small class="border-secondary rounded-1 text-secondary border px-2 py-1">{{ $film.getSing() }}</small></p>
						@endif -->
                            <div>
                                <div>
                                    <CountryFlag v-for="country in film.countries" is-text :country="country" />
                                </div>

                                <div class="mt-2">
                                    <span v-for="genre in film.genres" class="badge text-bg-secondary me-1">{{ genre.name }}</span>
                                </div>
                            </div>

                            <p class="mt-3">
                                <em>{{ film.slogan }}</em>
                            </p>
                            <p>{{ film.description }}</p>
                            <div class="mt-3">
                                <a class="btn btn-outline-secondary btn-sm" v-if="film.serial" :href="`https://www.kinopoisk.ru/series/${film.id}`" target="_blank">Кинопоиск</a>
                                <a class="btn btn-outline-secondary btn-sm" v-else :href="`https://www.kinopoisk.ru/film/${film.id}`" target="_blank">Кинопоиск</a>
                                <a v-if="film.imdb_id" class="btn btn-outline-secondary btn-sm ms-2" :href="`https://www.imdb.com/title/${film.imdb_id}`" target="_blank">IMDB</a>
                            </div>
                            <p class="mt-3"><strong>Год</strong>: {{ film.year }}</p>
                            <p><strong>Длина</strong>: {{ film.length }} мин.</p>

                            <div class="mt-4" v-if="film.theaters?.length">
                                <h5>Посмотреть</h5>
                                <div class="row g-1">
                                    <div v-for="theater in film.theaters" class="col-auto">
                                        <a style="display: inline-block; overflow: hidden;" class="btn btn-outline-secondary btn-sm ps-0 py-0" target="_blank" :href="theater.pivot.url">
                                            <img v-if="theater.images?.logo?.[0]?.urls?.origin" class="img-fluid me-1" width="30" :src="theater.images.logo[0].urls.origin" alt="">
                                            <span>{{ theater.name }}</span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="col-sm-3">
                            <div class="card mt-4">
                                <div class="card-header">Оценки</div>
                                <ul class="list-group list-group-flush">
                                    <template v-for="(data, name) in film.rating">
                                        <li v-if="data.review" class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="me-auto ms-2">
                                                {{ name }}
                                                <span class="small" v-if="data.count">({{ votes(data.count) }})</span>
                                            </div>
                                            <span class="badge bg-primary rounded-pill">{{ data.review }}</span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" :class="{ 'show active': currentTab == 'awards' }">
                    <div class="mt-3">
                        <table class="table-hover table-striped mb-0 table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Название</th>
                                    <th>Год</th>
                                    <th>Номинация</th>
                                    <th>Выиграл</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="award in film.awards">
                                    <tr v-for="nomination in award.nominations">
                                        <td>
                                            <img v-if="award?.images?.image?.[0]?.urls?.origin" :class="{ 'not-win': !nomination.is_win }" height="50" :src="award?.images?.image?.[0]?.urls?.origin">
                                            <div v-else class="award-badge rounded-circle" :class="{ 'bg-warning': nomination.is_win, 'bg-secondary': !nomination.is_win }"></div>
                                        </td>
                                        <td>{{ award.name }}</td>
                                        <td>{{ nomination.year }}</td>
                                        <td>
                                            <div>{{ nomination.name }}</div>
                                            <template v-for="person in nomination?.persons">
                                                <router-link :to="{ name: 'persons.show', params: { person: person.id } }" class="text-secondary small me-2">{{ person.name.ru }}</router-link>
                                            </template>
                                        </td>
                                        <td class="position-relative">
                                            {{ nomination.is_win ? 'Да' : 'Нет' }}
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
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

                <div class="tab-pane fade" :class="{ 'show active': currentTab == 'staff' }">
                    <ul class="nav nav-tabs mt-4" role="tablist">
                        <li v-for="(staff, profession) in film.professions" class="nav-item">
                            <button class="nav-link" :class="{ 'active': currentProfession == profession }" @click="currentProfession = profession" type="button" role="tab">{{ professionName(profession) }}</button>
                        </li>
                    </ul>

                    <div class="tab-content mt-3" id="myTabContent">
                        <div v-for="(staff, profession) in film.professions" class="tab-pane fade" :class="{ 'show active': currentProfession == profession }">
                            <div class="row">
                                <div v-for="person in film.persons.filter(person => staff.includes(person.id))" class="col-sm-2 my-2">
                                    <PersonCard :person="person" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-3" v-for="related_film in film.related_films">
            <FilmCard :film="related_film" :related="related_film.pivot.type" />
        </div>
    </div>
</template>

<script setup>
    import FilmCard from '@app/components/FilmCard.vue'
    import PersonCard from '@app/components/PersonCard.vue'

    import { useRoute } from 'vue-router'
    import { filmShow } from '@app/api/films'
    import { computed, ref, watch } from 'vue'
    import dayjs from '@app/bootstrap/dayjs'

    import AgeLimit from '@app/components/AgeLimit.vue'

    const route = useRoute()
    const filmId = ref(route.params?.film)
    const film = ref({})
    const currentTab = ref('main')
    const currentProfession = ref('WRITER')
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

    function votes(votes) {
        if (votes > 1000000) {
            return Math.round(votes / 1000000) + ' млн.'
        }

        if (votes > 1000) {
            return Math.round(votes / 1000) + ' тыс.'
        }

        return votes
    }

    function professionName(profession) {
        switch (profession) {
            case 'WRITER': return 'Сценарист'
            case 'OPERATOR': return 'Оператор'
            case 'EDITOR': return 'Монтажёр'
            case 'COMPOSER': return 'Композитор'
            case 'PRODUCER_USSR': return 'Продюсер (в СССР)'
            case 'TRANSLATOR': return 'Переводчик'
            case 'DIRECTOR': return 'Режиссёр'
            case 'DESIGN': return 'Художник-постановщик'
            case 'PRODUCER': return 'Продюсер'
            case 'ACTOR': return 'Актёр'
            case 'VOICE_DIRECTOR': return 'Режиссёр дубляжа'
            case 'UNKNOWN': return 'Неизвестно'
        }
    }

    const name = computed(() => {
        if (film.value.name?.ru) {
            return film.value.name.ru
        }

        return film.value.name?.en ? film.value.name.en : film.value.name?.original
    })

    watch(route, () => {
        if (route.params.film != filmId.value) {
            filmId.value = route.params.film
            loadFilm()
        }
    })
</script>

<style lang="scss" scoped>
    .poster {
        // max-width: 28vw;
    }

    .not-win {
        filter: grayscale(100%);
    }

    .award-badge {
        display: inline-block;
        width: 10px;
        height: 10px;
    }
</style>
