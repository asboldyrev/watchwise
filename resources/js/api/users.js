import request from "@app/api/request";

export function userCurrent() {
    return request('/api/users/current')
}

export function userWatchlistsUpdate(data) {
    return request('/api/users/watchlists', data)
}
