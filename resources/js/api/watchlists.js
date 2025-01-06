import request from "@app/api/request";

export function watchlistList(data) {
    return request('/api/watchlists/list', data)
}

export function watchlistStore(data) {
    return request('/api/watchlists/store', data)
}

export function watchlistShow(id) {
    return request(`/api/watchlists/${id}/show`)
}

export function watchlistUpdate(id, data) {
    return request(`/api/watchlists/${id}/update`, data)
}

export function watchlistDelete(id) {
    return request(`/api/watchlists/${id}/delete`)
}
