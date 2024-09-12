import {AUTH_ENDPOINT} from "~/lib/constants";
export default defineNuxtRouteMiddleware( async (to, from) => {
    let accessToken = useCookie('access_token').value

    if(!accessToken) {
        const refreshToken = useCookie('refresh_token').value
        if(!refreshToken) {
            return navigateTo('/login')
        }
        try {
            const data: any = await $fetch(`${AUTH_ENDPOINT}refresh-token`, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${refreshToken}`
                },
            })

            const cookieOptions: any = {
                sameSite: 'lax',
                path: '/',
                domain: 'localhost',
                secure: false,
                httpOnly: false,
            }

            useCookie('access_token', {
                ...cookieOptions,
                expires: new Date(data.tokens.access_token.expires_at)
            }).value = data.tokens.access_token.token


            useCookie('refresh_token', {
                ...cookieOptions,
                expires: new Date(data.tokens.refresh_token.expires_at)
            }).value = data.tokens.refresh_token.token

        } catch (e: any) {
            return navigateTo('/login')
        }
    }
})