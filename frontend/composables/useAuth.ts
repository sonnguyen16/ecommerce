import type {User} from "~/lib/schema";
import {USER_KEY} from "~/lib/constants";

export const useAuth = () => {
    const user = useState<User | null>(USER_KEY);

    const getUser = async () => {
       if(!user.value){
           await fetchUser()
       }
       return user.value
    }

    const fetchUser = async () => {
        const { data } = await useServerFetch("profile");
        user.value = data.value ?? null;
    }

    return { user, getUser, fetchUser };
};