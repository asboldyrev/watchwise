import request from "@app/api/request";

export function personList(data) {
    return request('/api/persons/list', data)
}

export function personShow(id) {
    return request(`/api/persons/${id}/show`)
}
