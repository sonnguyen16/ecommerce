import { refreshTokenFunc, isTokenValid } from "@/utils/common";
export default defineEventHandler(async (event) => {
    const { url } = event.node.req;
    if (url?.startsWith("/api") && event.node.req.headers.authorization) {
        return;
    }
    const { apiUrl } = useRuntimeConfig().public;
    let accessToken = getCookie(event, 'access_token');
    let expireTime = getCookie(event, 'expire_time');
    if (!isTokenValid(expireTime ?? null) || !accessToken) {
        accessToken = await refreshTokenFunc(event, apiUrl);
    }
    event.node.req.headers.authorization = "Bearer " + accessToken;
});