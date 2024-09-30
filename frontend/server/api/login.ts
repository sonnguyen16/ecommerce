import { H3Event } from "h3";

export default defineEventHandler(async (event: H3Event) => {
    const { apiUrl } = useRuntimeConfig().public;
    const body = await readBody(event);

    try {
        const { tokens } : any = await $fetch("login", {
            baseURL: apiUrl,
            method: "POST",
            body
        })


        if (tokens) {
            setCookie(event, 'access_token', tokens.access_token.token, {
                domain: 'localhost',
                httpOnly: true,
                secure: true,
                sameSite: "strict",
                expires: new Date(tokens.access_token.expires_at),
            })
            setCookie(event, 'refresh_token', tokens.refresh_token.token, {
                domain: 'localhost',
                httpOnly: true,
                secure: true,
                sameSite: "strict",
                expires: new Date(tokens.refresh_token.expires_at),
            })
        }
    } catch (error) {
        throw error;
    }
});
