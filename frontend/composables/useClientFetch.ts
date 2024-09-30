import type { UnwrapRef } from "vue";
import type {NitroFetchOptions} from "nitropack";
import type {FetchContext, FetchResponse} from "ofetch";

export const useClientFetch = async <T>(
    target: string,
    options?: NitroFetchOptions<"json">
) => {
    const data = ref<T | null>(null);
    const error = ref<Error | null>(null);

    try {
        const response = await $fetch<T>(target, {
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
            }
        });
        data.value = (response ?? null) as UnwrapRef<T>;
    } catch (err) {
        error.value = err as Error;
    }

    return {
        data,
        error,
    };
};