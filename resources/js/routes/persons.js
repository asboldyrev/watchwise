import PersonShow from "@app/pages/persons/PersonShow.vue"
import PersonList from "@app/pages/persons/PersonList.vue"

export default [
    {
        path: 'persons',
        children: [
            {
                path: '',
                name: 'persons.list',
                component: PersonList,
            },
            {
                path: ':person',
                name: 'persons.show',
                component: PersonShow,
            },

        ],
    },
]
