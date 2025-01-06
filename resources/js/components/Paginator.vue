<template>
    <nav aria-label="Pagination" v-if="totalPages > 1">
        <ul class="pagination">
            <!-- Кнопка "Предыдущая" -->
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <router-link :to="getPageRoute(currentPage - 1)" class="page-link" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </router-link>
            </li>

            <!-- Перебираем массив страниц и разрывов -->
            <template v-for="(item, index) in pages" :key="index">
                <!-- Разрыв "..." -->
                <li v-if="item === '...'" class="page-item disabled">
                    <span class="page-link">...</span>
                </li>

                <!-- Номера страниц -->
                <li v-else class="page-item" :class="{ active: currentPage === item }">
                    <router-link :to="getPageRoute(item)" class="page-link">
                        {{ item }}
                    </router-link>
                </li>
            </template>

            <!-- Кнопка "Следующая" -->
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <router-link :to="getPageRoute(currentPage + 1)" class="page-link" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script setup>
    import { computed } from 'vue'

    // Пропсы компонента
    const props = defineProps({
        currentPage: {
            type: Number,
            required: true,
        },
        totalPages: {
            type: Number,
            required: true,
        },
        routeName: {
            type: String,
            required: true,
        },
        params: {
            type: Object,
            required: false,
            default: {},
        }
    })

    // Функция для создания ссылки на страницу
    const getPageRoute = (page) => {
        return { name: props.routeName, params: props.params, query: { page } }
    }

    // Генерация номеров страниц с разрывами
    const pages = computed(() => {
        const current = props.currentPage
        const total = props.totalPages
        const range = []

        if (total <= 7) {
            // Если страниц <= 7, показываем все страницы
            for (let i = 1; i <= total; i++) range.push(i)
        } else {
            // Всегда показываем первые 2 и последние 2 страницы
            range.push(1, 2)

            if (current > 4) {
                range.push('...')
            }

            // Добавляем текущую страницу и страницы вокруг неё
            for (let i = current - 1; i <= current + 1; i++) {
                if (i > 2 && i < total - 1) {
                    range.push(i)
                }
            }

            if (current < total - 3) {
                range.push('...')
            }

            // Добавляем последние 2 страницы
            range.push(total - 1, total)
        }

        return range
    })
</script>

<style scoped>
    .page-item.disabled .page-link {
        pointer-events: none;
        color: #6c757d;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>
