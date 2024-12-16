<template>
    <router-link :to="{ name: 'persons.show', params: { person: person.id } }" class="card text-decoration-none">
        <div class="row g-0">
            <div class="col-md-4">
                <img loading="lazy" v-if="person.images?.poster?.[0]?.urls?.origin" class="img-fluid" :src="person.images?.poster?.[0]?.urls?.origin" :alt="person.name?.ru ? person.name.ru : person.name.en">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ person.name?.ru ? person.name.ru : person.name.en }}</h5>
                    <p v-if="person?.pivot?.description" class="card-text description">{{ person.pivot.description }}</p>
                    <p v-if="person?.films_count" class="card-text">
                        <small class="text-body-secondary">
                            <strong>Фильмов</strong>: {{ person?.films_count }}
                        </small>
                    </p>
                    <p v-if="person?.nominations_count" class="card-text">
                        <small class="text-body-secondary">
                            <strong>Наград</strong>: {{ person?.nominations_count }}
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </router-link>
</template>

<script setup>
    defineProps({
        person: {
            type: Object,
            required: true,
        }
    })
</script>

<style lang="scss" scoped>
    .card {
        text-decoration: none;
        overflow: hidden;
    }
</style>
