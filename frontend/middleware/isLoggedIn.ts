import {AUTH_ENDPOINT} from "~/lib/constants";

export default defineNuxtRouteMiddleware(async (to, from) => {
    try {
        const user: any = await $fetch(`${AUTH_ENDPOINT}profile`, {
            headers: {
                'Authorization': `Bearer ${useCookie('access_token').value}`,
                'Accept': 'application/json'
            }
        })

        if(to.path.includes('/login')){
            if(user.id){
                return navigateTo('/')
            }
        }
    }catch (e: any){
        return
    }
})
