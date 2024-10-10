import {H3Event, setCookie} from "h3";

export default defineEventHandler(async (event: H3Event) => {
    const { apiUrl } = useRuntimeConfig().private;
    const body = await readBody(event);

    try {
        const { tokens } : any = await $fetch("login", {
            baseURL: apiUrl,
            method: "POST",
            body
        })

        let { cookieDomain } = useRuntimeConfig().public;

        if (tokens) {
            setCookie(event, 'access_token', tokens.access_token.token, {
                domain: cookieDomain,
                httpOnly: true,
                secure: true,
                sameSite: "strict",
                expires: new Date(tokens.access_token.expires_at),
            });
            setCookie(event, 'refresh_token', tokens.refresh_token.token, {
                domain: cookieDomain,
                httpOnly: true,
                secure: true,
                sameSite: "strict",
                expires: new Date(tokens.refresh_token.expires_at),
            });
            setCookie(event, 'expire_time', tokens.expire_time, {
                domain: cookieDomain,
                httpOnly: true,
                secure: true,
                sameSite: "strict",
                expires: new Date(tokens.access_token.expires_at),
            });
            return tokens.access_token.token;
        }
    } catch (error: any) {
        throw createError(error)
    }
});
