<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">WatchWise</a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <router-link activeClass="active" :to="{ name: 'films.list' }" class="nav-link" aria-current="page">Фильмы</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link activeClass="active" :to="{ name: 'persons.list' }" class="nav-link" aria-current="page">Люди</router-link>
                    </li>
                </ul>
            </div>


            <div class="navbar-collapse collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0"></ul>

                <SearchForm />
            </div>
        </div>
    </nav>

</template>

<script setup>
    import SearchForm from '@app/components/SearchForm.vue'

</script>

<style lang="scss" scoped></style>
