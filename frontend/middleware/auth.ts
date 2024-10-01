export default defineNuxtRouteMiddleware(async () => {
    const { isLoggedIn } = useAuth()

    if(!await isLoggedIn()){
        return navigateTo("/signin");
    }
})