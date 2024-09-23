export const BASE_URL = 'http://127.0.0.1:8000/';
export const API_ENDPOINT = BASE_URL + 'api/';
export const MEDIA_ENDPOINT = BASE_URL + 'storage/';
export const AUTH_ENDPOINT = API_ENDPOINT + 'auth/';

export const headers = {
    'Accept': 'application/json',
}
export const cookieOptions: any = {
    sameSite: 'lax',
    path: '/',
    domain: 'localhost',
    secure: false,
    httpOnly: false,
}
export const categories = [
    {
        img: "/topdeal.png",
        name: "TOP DEAL"
    },
    {
        img: "/trading.png",
        name: "Tiki Trading"
    },
    {
        img: "/hot.png",
        name: "Coupon siêu hot"
    },
    {
        img: "/sale.png",
        name: "Siêu sale"
    },
    {
        img: "/hot2.png",
        name: "Hàng quốc tế"
    },
    {
        img: "/topdeal.png",
        name: "TOP DEAL"
    },
    {
        img: "/trading.png",
        name: "Tiki Trading"
    },
    {
        img: "/hot.png",
        name: "Coupon siêu hot"
    },
    {
        img: "/sale.png",
        name: "Siêu sale"
    },
    {
        img: "/hot2.png",
        name: "Hàng quốc tế"
    }
]