import {AUTH_ENDPOINT, cookieOptions, headers} from "~/lib/constants";
export default defineNuxtRouteMiddleware( async (to, from) => {
    let accessToken = useCookie('access_token').value

    if(!accessToken) {
        const refreshToken = useCookie('refresh_token').value
        if(!refreshToken) {
            if(to.path.includes('/cart')) return
            return navigateTo('/login')
        }
        try {
            const data: any = await $fetch(`${AUTH_ENDPOINT}refresh-token`, {
                headers: {
                    ...headers,
                    Authorization: `Bearer ${refreshToken}`
                },
            })

            useCookie('access_token', {
                ...cookieOptions,
                expires: new Date(data.tokens.access_token.expires_at)
            }).value = data.tokens.access_token.token


            useCookie('refresh_token', {
                ...cookieOptions,
                expires: new Date(data.tokens.refresh_token.expires_at)
            }).value = data.tokens.refresh_token.token

        } catch (e: any) {
            if(to.path.includes('/cart')) return
            return navigateTo('/login')
        }
    }
})