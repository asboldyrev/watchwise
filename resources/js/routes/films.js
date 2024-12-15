import FilmList from "@app/pages/films/FilmList.vue"
import FilmShow from "@app/pages/films/FilmShow.vue"

export default [
    {
        path: 'films',
        children: [
            {
                path: '',
                name: 'films.list',
                component: FilmList,
            },
            {
                path: ':film',
                name: 'films.show',
                component: FilmShow,
            },
        ],
    },
]
