<template>
    <div class="row mt-3 mb-4 g-3">
        <div class="col-sm-2" v-for="person in persons">
            <PersonCard :person="person" />
        </div>
    </div>

    <Paginator :currentPage="parseInt(currentPage)" :totalPages="totalPages" routeName="persons.list" />
</template>

<script setup>
    import PersonCard from '@app/components/PersonCard.vue'

    import { personList } from '@app/api/persons'
    import { ref, watch } from 'vue'
    import { useRoute } from 'vue-router'

    const route = useRoute()
    const persons = ref([])
    const currentPage = ref(route.query?.page || 1)
    const totalPages = ref(1)

    function loadPersons() {
        personList({ page: currentPage.value }).then(response => {
            persons.value = response.data
            currentPage.value = response.meta.current_page
            totalPages.value = response.meta.last_page
        })
    }

    loadPersons()

    watch(route, (newRoute) => {
        currentPage.value = newRoute.query?.page
        loadPersons()
    })

</script>

<style lang="scss" scoped></style>
