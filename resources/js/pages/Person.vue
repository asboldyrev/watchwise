<template>
    <div class="row my-2">
        <div class="col-sm-3">
            <div class="mb-4">
                <img class="poster rounded img-fluid" :src="person.images?.poster?.[0]?.urls?.origin">
            </div>
        </div>

        <div class="col-sm-9">
            <h1>{{ name }}</h1>
            <h3 v-if="person.name?.ru && (person.name.ru || person.name.original)" class="h4 text-secondary">
                {{ person.name.en ? person.name.en : person.name.original }}
            </h3>

            <div class="mt-4">
                <p><strong>Возраст</strong>: {{ person.age }}</p>
                <p><strong>Рождение</strong>: {{ dayjs(person.birthday).format('D MMMM YYYY') }} <small class="text-secondary" v-if="person?.birth_place">{{ person.birth_place }}</small></p>
                <p v-if="person?.death"><strong>Смерть</strong>: {{ dayjs(person.death).format('D MMMM YYYY') }} <small class="text-secondary" v-if="person?.birth_place">{{ person.death_place }}</small></p>
                <p v-if="person.profession">{{ person.profession }}</p>
                <div v-if="person.facts?.length">
                    <strong>Факты</strong>
                    <ul>
                        <li v-for="fact in person.facts">{{ fact }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <h3>Фильмы</h3>
    <div class="row">
        <div class="col-sm-3" v-for="film in person.films">
            <FilmCard :film="film" />
        </div>
    </div>

    <!-- {{ person }} -->
</template>

<script setup>
    import FilmCard from '@app/components/FilmCard.vue'

    import { useRoute } from 'vue-router'
    import { personShow } from '@app/api/persons'
    import { computed, ref } from 'vue'

    import dayjs from '@app/bootstrap/dayjs'

    const route = useRoute()
    const personId = ref(route.params?.person)
    const person = ref({})

    personShow(personId.value).then(response => {
        person.value = response.data
    })

    const name = computed(() => {
        if (person.value.name?.ru) {
            return person.value.name.ru
        }

        return person.value.name?.en ? person.value.name.en : person.value.name?.original
    })

</script>

<style lang="scss" scoped></style>
