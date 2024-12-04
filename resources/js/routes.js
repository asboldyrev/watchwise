import Search from "@app/pages/Search.vue"
import Film from "@app/pages/Film.vue"
import MainPage from "@app/pages/MainPage.vue"

export default [
    {
        path: '/',
        children: [
            {
                path: '',
                name: 'index',
                component: MainPage,
            },
            {
                path: 'search',
                name: 'search',
                component: Search,
            },
            {
                path: 'films/:film',
                name: 'films.show',
                component: Film,
            },
        ],
    },
]
