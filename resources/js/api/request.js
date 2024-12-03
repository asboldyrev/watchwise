import simpleFetch from '@app/api/simple-fetch'

export default async function (uri, body = {}, token) {
    const headers = {
        Accept: 'application/json',
        'Content-Type': 'application/json;charset=utf-8',
    }

    const data = {
        method: 'post',
        headers,
        body: '',
    }

    if (body) {
        data.body = JSON.stringify(body)
    }

    if (token) {
        data.headers.Authorization = `Bearer ${token}`
    }

    return await simpleFetch(uri, data)
}
