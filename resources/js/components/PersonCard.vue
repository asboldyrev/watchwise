<template>
    <router-link :to="{ name: 'persons.show', params: { person: person.id } }" class="card text-decoration-none">
        <img loading="lazy" v-if="person.images?.poster?.[0]?.urls?.origin" class="card-img-top img-fluid" :src="person.images?.poster?.[0]?.urls?.origin" :alt="person.name?.ru ? person.name.ru : person.name.en">
        <div class="card-body">
            <h5 class="card-title">{{ person.name?.ru ? person.name.ru : person.name.en }}</h5>
        </div>
        <ul class="list-group list-group-flush">
            <!-- <li class="list-group-item">{{ person.pivot.profession_text }}</li> -->
            <li v-if="person?.pivot?.description" class="list-group-item">{{ person.pivot.description }}</li>
        </ul>
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

<style lang="scss" scoped></style>
