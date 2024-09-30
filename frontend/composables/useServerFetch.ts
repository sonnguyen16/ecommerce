import type { UseFetchOptions } from "#app";
import type {FetchContext, FetchResponse} from "ofetch";

export const useServerFetch = async <T = any>(
    target: string,
    options?: UseFetchOptions<T>
) => {
    return useFetch(target, {
        ...options,
        baseURL: "/api",
        headers: {
            "Accept": "application/json",
            ...options?.headers,
        },
        onResponseError(context: FetchContext & { response: FetchResponse<ResponseType> }) {
            if(context.response.status === 401) {
                navigateTo("/login")
            }
        },
        transform: (response: any): T => {
            return response.data ? response.data : response;
        },
    });
};