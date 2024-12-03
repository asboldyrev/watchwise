import Search from "@app/pages/Search.vue"
import Film from "@app/pages/Film.vue"

export default [
    {
        path: '/',
        children: [
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
