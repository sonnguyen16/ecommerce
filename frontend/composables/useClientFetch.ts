import type { UnwrapRef } from "vue";
import type {NitroFetchOptions} from "nitropack";
import {type FetchContext} from "ofetch";

export const useClientFetch = async <T = any>(
    target: string,
    options?: NitroFetchOptions<"json">
) => {
    const data = ref<T | null>(null);
    const error = ref<Error | null>(null);

    try {
        const response = await $fetch(target, {
            ...options,
            baseURL: "/api",
            headers: {
                "Accept": "application/json",
                ...useRequestHeaders(),
            },
            onResponseError(context: FetchContext){
                console.error("Error response", context.response?._data);
            },
            onResponse(context: FetchContext){
                console.log(context.response)
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