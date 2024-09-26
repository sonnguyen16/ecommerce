import {API_ENDPOINT, cookieOptions, headers} from "~/lib/constants";
import {type FetchContext, type FetchResponse} from "ofetch";
import {type TokenResponse} from "~/lib/schema";


export default async function usePostData<T>({
    url,
    body = null,
    requiresToken = false,
    method = 'POST',
} : {
    url: string,
    body?: any,
    requiresToken?: boolean,
    method?: any
}): Promise<any> {

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

    const auth_urls = ['auth/login', 'auth/register']

    return await $fetch(`${API_ENDPOINT}${url}`, {
        method,
        body,
        headers: {
            ...headers,
            'Authorization': requiresToken ? `Bearer ${useCookie('access_token').value}` : ''
        },
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
                            ...context.options.headers,
                            Authorization: requiresToken ? `Bearer ${tokens.access_token.token}` : ''
                        }
                    }catch (e: any) {
                        navigateTo('/login')
                    }
                }
            }
        },
        onResponseError(context: FetchContext & { response: FetchResponse<any> }){
            if (context.response.status === 401) {
                navigateTo('/login')
            }
        },
        onResponse({response}: { response: FetchResponse<any> }) {
            if (response.status === 200 && auth_urls.includes(url)) {
                setCookie(response._data.tokens as TokenResponse)
            }
        },
    });
}
