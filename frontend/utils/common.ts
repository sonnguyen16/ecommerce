import { H3Event, setCookie, getCookie } from "h3";

export const isTokenValid = (expTime: string | null) => {
    if (!expTime) return false;
    const currentTime = Math.floor(Date.now() / 1000);
    const bufferTime = 10; // Thời gian buffer 10 giây
    return Number(expTime) >= (currentTime + bufferTime);
}

export const refreshTokenFunc = async (event: H3Event, apiUrl: string) => {
    const refreshToken = getCookie(event,'refresh_token');

    if (!refreshToken) {
        return false;
    }

    try {
        const { tokens }: any = await $fetch("refresh-token", {
            baseURL: apiUrl,
            headers: {
                "Authorization": `Bearer ${refreshToken}`,
                "Accept": "application/json",
            }
        });

        const { cookieDomain } = useRuntimeConfig().public;

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
    } catch (error) {
        console.error(error);
        deleteCookie(event, 'refresh_token');
        deleteCookie(event, 'access_token');
        deleteCookie(event, 'expire_time');
        return null;
    }
};
