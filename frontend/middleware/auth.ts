export default defineNuxtRouteMiddleware(async (to) => {
    const user = await useAuth().getUser()

    if(to.path === "/signin" || to.path === "/signup" && user){
        return navigateTo("/");
    }

    if(!user){
        return navigateTo("/signin");
    }
})