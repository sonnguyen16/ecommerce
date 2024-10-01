import type {User} from "~/lib/schema";

export const useAuth = () => {
    const user = useState<User | null>('user');

    const getUser = async () => {
        const { data } : any = await useClientFetch<{ data: User }>("profile");
        user.value = data.value ?? null;
    }

    const isLoggedIn = async () => {
        await getUser();
        return Boolean(user.value)
    }

    return { user, getUser, isLoggedIn };
};