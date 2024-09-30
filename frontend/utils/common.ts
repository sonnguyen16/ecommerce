import { H3Event, setCookie, getCookie } from "h3";
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


        if (tokens) {
            setCookie(event, 'access_token', tokens.access_token.token, {
                domain: 'localhost',
                httpOnly: true,
                secure: true,
                sameSite: "strict",
                expires: new Date(tokens.access_token.expires_at),
            });
            setCookie(event, 'refresh_token', tokens.refresh_token.token, {
                domain: 'localhost',
                httpOnly: true,
                secure: true,
                sameSite: "strict",
                expires: new Date(tokens.refresh_token.expires_at),
            });
            return tokens.access_token.token;
        }
    } catch (error) {
        deleteCookie(event, 'refresh_token');
        deleteCookie(event, 'access_token');
        return null;
    }
};