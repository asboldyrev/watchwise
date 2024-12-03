<template>
    <div class="position-absolute ms-1 mt-1" v-if="film.mpaa || film.age">
        <div v-if="film.mpaa" class="d-inline-block bg-light text-uppercase rounded border border-2 border-black px-2 py-1 text-center text-black" style="min-width: 40px;">
            <small>
                <strong>{{ film.mpaa }}</strong>
            </small>
        </div>
        <div v-if="film.age" class="d-inline-block bg-light text-uppercase rounded border border-2 border-black px-2 py-1 text-center text-black" style="min-width: 40px;">
            <small>
                <strong>{{ film.age }}</strong>
            </small>
        </div>
    </div>
</template>

<script setup>
    defineProps({
        film: {
            type: Object,
            required: true,
        }
    })
</script>

<style lang="scss" scoped></style>
