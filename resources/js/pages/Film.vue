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
                    <button class="nav-link" :class="{ 'active': currentTab == 'main' }" type="button" role="tab">Основное</button>
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

                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="home-tab" data-bs-toggle="tab" data-bs-target="#seasons" type="button" role="tab">Сезоны</button>
                </li> -->
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="main" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
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

                    <!-- <div class="mt-4">
						@if ($film.imdb_id)
							<a class="me-3" href="{{ 'https://www.imdb.com/title/' . $film.imdb_id }}" target="_blank">IMDB</a>
						@endif

						@if ($film.serial)
							<a href="{{ 'https://www.kinopoisk.ru/series/' . $film.id }}" target="_blank">Кинопоиск</a>
						@else
							<a href="{{ 'https://www.kinopoisk.ru/film/840725' . $film.id }}" target="_blank">Кинопоиск</a>
						@endif
					</div> -->

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

                        <!-- @if ($film.onlineTheaters->count())
							<div class="col-sm-6">
								<div class="card mt-4">
									<div class="card-header">Онлайн кинотеатры</div>
									<ul class="list-group list-group-flush">
										@foreach ($film.onlineTheaters as $onlineTheater)
											<li class="list-group-item">
												<a class="text-decoration-none mx-2" href="{{ $onlineTheater->pivot->url }}">
													@if ($onlineTheater->media)
														<img class="img-fluid" width="30" src="{{ $onlineTheater->getFirstMediaUrl('logo', 'logo') }}" alt="">
													@endif
													<span>{{ $onlineTheater->name }}</span>
												</a>
											</li>
										@endforeach
									</ul>
								</div>
							</div>
						@endif -->
                    </div>
                </div>
            </div>
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
