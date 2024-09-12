import {API_ENDPOINT} from "~/lib/constants";
import type { NuxtApp } from "#app";

export default async function useFetchData<T>({
    url, requiresToken = false,
} : {
    url: string,
    requiresToken?: boolean,
}): Promise<{
    data: T | any;
    status: string | any;
    error: Error | any;
}> {

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }

    const { data, status, error } = await useFetch(`${API_ENDPOINT}${url}`, {
        method: 'GET',
        headers: {
            ...headers,
            'Authorization': requiresToken ? `Bearer ${useCookie('access_token').value}` : ''
        },
        getCachedData(key:string, nuxtApp: NuxtApp) {
            return nuxtApp.payload.data[key] || nuxtApp.static.data[key]
        }
    })

    return {
        data,
        status,
        error,
    };
}
