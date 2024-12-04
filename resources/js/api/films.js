import request from "@app/api/request";

export function filmList(data) {
    return request('/api/films/list', data)
}
export function filmShow(id) {
    return request(`/api/films/${id}/show`)
}
