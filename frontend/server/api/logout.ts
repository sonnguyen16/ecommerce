import { H3Event } from "h3";

export default defineEventHandler(async (event: H3Event) => {
    const accessToken = getCookie(event, 'access_token');
    const { apiUrl } = useRuntimeConfig().private;

    try {
        await $fetch("logout", {
            baseURL: apiUrl,
            headers: {
                Authorization: `Bearer ${accessToken}`,
            }
        })

        deleteCookie(event, 'access_token')
        deleteCookie(event, 'refresh_token')
        deleteCookie(event, 'expire_time')
    } catch (error: any) {
        throw createError(error)
    }
});
