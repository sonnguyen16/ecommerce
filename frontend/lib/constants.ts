export const BASE_URL = 'http://localhost:8000/';
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
