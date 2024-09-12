import {API_ENDPOINT} from "~/lib/constants";
import {type FetchResponse} from "ofetch";
import {type TokenResponse} from "~/lib/schema";

export default async function usePostData<T>({
    url,
    body = null,
    requiresToken = false,
    method = 'POST'
} : {
    url: string,
    body?: any,
    requiresToken?: boolean,
    method?: any
}): Promise<any> {

    const cookieOptions: any = {
        sameSite: 'lax',
        path: '/',
        domain: 'localhost',
        secure: false,
        httpOnly: false,
    }

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
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

    const auth_urls = ['auth/login', 'auth/register', 'auth/refresh-token']

    return await $fetch(`${API_ENDPOINT}${url}`, {
        method,
        body,
        headers: {
            ...headers,
            'Authorization': requiresToken ? `Bearer ${useCookie('access_token').value}` : ''
        },
        onResponse({response}: { response: FetchResponse<any> }) {
            if (response.status === 200 && auth_urls.includes(url)) {
                setCookie(response._data.tokens as TokenResponse)
            }
        }
    });
}
