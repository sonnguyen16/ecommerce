import type { UseFetchOptions } from "#app";

export const useServerFetch = async <T = any>(
    target: string,
    options?: UseFetchOptions<T>
) => {
    return useFetch(target, {
        ...options,
        baseURL: "/api",
        headers: {
            "Accept": "application/json",
            ...useRequestHeaders(),
        }
    });
};