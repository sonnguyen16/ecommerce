import {API_ENDPOINT, cookieOptions, headers} from "~/lib/constants";
import type {NuxtApp} from "#app";
import type {FetchContext} from "ofetch";

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

    function setCookie(data: any){
        useCookie('access_token', {
            ...cookieOptions,
            expires: new Date(data.access_token.expires_at)
        }).value = data.access_token.token

        useCookie('refresh_token', {
            ...cookieOptions,
            expires: new Date(data.refresh_token.expires_at)
        }).value = data.refresh_token.token
    }

    const { data, status, error } = await useFetch(`${API_ENDPOINT}${url}`,
    {
        method: 'GET',
        headers: {
            ...headers,
            'Authorization': requiresToken ? `Bearer ${useCookie('access_token').value}` : ''
        },
        server,
        deep: true,
        async onRequest(context: FetchContext){
            if(requiresToken) {
                const accessToken = useCookie('access_token').value
                if (!accessToken) {
                    const refreshToken = useCookie('refresh_token').value
                    if (!refreshToken) {
                        navigateTo('/login')
                    }
                    try{
                        const { tokens }: any = await $fetch(`${API_ENDPOINT}auth/refresh-token`, {
                            headers: {
                                ...headers,
                                'Authorization': `Bearer ${refreshToken}`
                            }
                        })

                        setCookie(tokens)

                        context.options.headers = {
                            ...headers,
                            Authorization: requiresToken ? `Bearer ${tokens.access_token.token}` : ''
                        }
                    }catch (e: any) {
                        navigateTo('/login')
                    }
                }
            }
        },
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
