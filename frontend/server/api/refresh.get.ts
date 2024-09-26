import {AUTH_ENDPOINT, cookieOptions, headers} from "~/lib/constants";

export default defineEventHandler(async (event) => {
    const refreshToken = event.headers.get('Authorization')?.split(' ')[1]
        try {
            const { tokens }: any = await $fetch(`${AUTH_ENDPOINT}refresh-token`, {
                headers: {
                    ...headers,
                    Authorization: `Bearer ${refreshToken}`
                },
            })

            setCookie(event, 'access_token', tokens.access_token.token, {
                ...cookieOptions,
                expires: new Date(tokens.access_token.expires_at)
            })

            setCookie(event, 'refresh_token', tokens.refresh_token.token, {
                ...cookieOptions,
                expires: new Date(tokens.refresh_token.expires_at)
            })

            return tokens
        } catch (e: any) {
            return e
        }
})