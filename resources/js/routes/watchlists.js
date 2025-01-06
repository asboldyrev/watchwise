import WatchList from "@app/pages/Watchlist.vue"

export default [
    {
        path: 'watchlists',
        children: [
            {
                path: '',
                name: 'watchlists.list',
                component: WatchList,
            },
            {
                path: ':watchlist',
                name: 'watchlists.show',
                component: WatchList,
            },
        ]
    },
]
