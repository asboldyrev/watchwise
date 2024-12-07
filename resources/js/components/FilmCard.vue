<template>
    <router-link :to="{ name: 'films.show', params: { film: film.id } }" class="card mb-3">
        <AgeLimit :film="film" small />
        <div class="row g-0">
            <div class="col-md-4">
                <img loading="lazy" :src="film.images.poster?.[0]?.urls?.card" class="img-fluid rounded-start" alt="">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ name }} <span class="text-secondary">{{ film.year }}</span></h5>
                    <p class="card-text description">{{ film.description }}</p>
                    <p class="card-text"><small class="text-body-secondary">{{ genres }}</small></p>
                    <span class="badge text-bg-light" v-if="related">{{ relatedFilm }}</span>
                    <CountryFlag v-for="country in film.countries" :country="country" />
                </div>
            </div>
        </div>
    </router-link>
</template>

<script setup>
    import { computed } from 'vue'

    const props = defineProps({
        film: {
            type: Object,
            required: true
        },
        related: {
            type: Array,
            required: false,
            default: () => undefined
        }
    })

    const name = computed(() => {
        if (props.film.name?.ru) {
            return props.film.name.ru
        }

        return props.film.name?.en ? props.film.name.en : props.film.name?.original
    })

    const genres = computed(() => {
        return props.film.genres.map(genre => genre.name).join(', ')
    })

    const relatedFilm = computed(() => {
        const relation = {
            SEQUEL: 'сиквел',
            PREQUEL: 'приквел',
            REMAKE: 'ремейк',
            UNKNOWN: 'Неизвестно',
        }

        if (props.related) {
            return relation?.[props.related]
        }

        return undefined
    })

</script>

<style lang="scss" scoped>
    .card {
        text-decoration: none;
    }

    .description {
        overflow: hidden;
        text-overflow: ellipsis;
        box-orient: vertical;
        -moz-box-orient: vertical;
        -webkit-box-orient: vertical;
        display: -webkit-box;
        display: -moz-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
    }

    .img-fluid {
        height: 100%;
        object-fit: cover;
    }
</style>
