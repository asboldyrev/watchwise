import Search from "@app/pages/Search.vue"

import films from '@app/routes/films'
import persons from '@app/routes/persons'

export default [
    {
        path: '/',
        children: [
            {
                path: '',
                redirect: { name: 'films.list' },
            },
            {
                path: 'search',
                name: 'search',
                component: Search,
            },
            ...films,
            ...persons,
        ],
    },
]
