import {AUTH_ENDPOINT} from "~/lib/constants";

export default defineNuxtRouteMiddleware(async (to, from) => {
    try {
        const user: any = await $fetch(`${AUTH_ENDPOINT}profile`, {
            headers: {
                'Authorization': `Bearer ${useCookie('access_token').value}`,
                'Accept': 'application/json'
            }
        })

        if (to.path.startsWith('/manage')) {
            if (!user.shop) {
                return navigateTo('/')
            }
        }

    }catch (e: any){
        if(e.status === 401){
            return navigateTo('/')
        }
    }
})
