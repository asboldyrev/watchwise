<template>
    <router-link :to="{ name: 'films.show', params: { film: film.filmId } }" class="card mb-3">
        <div class="row g-0">
            <div class="col-md-4">
                <img loading="lazy" :src="film.posterUrlPreview" class="img-fluid rounded-start" alt="">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ name }} <span class="text-secondary">{{ film.year }}</span></h5>
                    <p class="card-text description">{{ film.description }}</p>
                    <p class="card-text"><small class="text-body-secondary">{{ genres }}</small></p>
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
        }
    })

    const name = computed(() => {
        if (props.film?.nameRu) {
            return props.film.nameRu
        }

        return props.film?.nameEn ? props.film.nameEn : props.film.name?.nameOriginal
    })

    const genres = computed(() => {
        return props.film.genres.map(genre => genre.genre).join(', ')
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
