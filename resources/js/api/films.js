import request from "@app/api/request";

export function filmShow(id) {
    return request(`/api/films/${id}/show`)
}
