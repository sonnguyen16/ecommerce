import {API_ENDPOINT} from "~/lib/constants";
import type {NuxtApp} from "#app";

export default async function useFetchData<T>({
    url,
    requiresToken = false,
    server = true,
    cache = true
} : {
    url: string,
    requiresToken?: boolean,
    server?: boolean,
    cache?: boolean
}): Promise<{
    data: T | any,
    status: string | any,
    error: any
}> {

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }

    const { data, status, error } = await useFetch(`${API_ENDPOINT}${url}`,
    {
        method: 'GET',
        headers: {
            ...headers,
            'Authorization': requiresToken ? `Bearer ${useCookie('access_token').value}` : ''
        },
        server,
        getCachedData(key: string, nuxtApp: NuxtApp) {
            if (cache) {
                return nuxtApp.payload.data[key] || nuxtApp.static.data[key]
            }
        }
    })

    return {
        data,
        status,
        error
    }
}
