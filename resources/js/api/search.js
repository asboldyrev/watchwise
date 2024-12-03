import request from "@app/api/request"

export function search(data) {
    return request('/api/films/search', data)
}
