import { refreshTokenFunc } from "@/utils/common";
export default defineEventHandler(async (event) => {
    const { url } = event.node.req;
    if (url?.startsWith("/api") && event.node.req.headers.authorization) {
        return;
    }
    const { apiUrl } = useRuntimeConfig().public;
    let accessToken = getCookie(event, 'access_token');
    if (!accessToken) {
        accessToken = await refreshTokenFunc(event, apiUrl);
    }
    event.node.req.headers.authorization = "Bearer " + accessToken;
});