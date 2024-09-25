import {defineStore} from 'pinia'

export const useUserStore = defineStore('user', {
    state: () => {
        return {
            user: null,
            provinces: [],
        }
    },
    actions: {
        async getUser() {
            if(!this.user) {
                const { data } = await useFetchData({url: 'auth/profile', requiresToken: true, server: false})
                this.user = data
            }
            return this.user
        },
        async getProvinces() {
            if(!this.provinces.length) {
                const { data } = await useFetchData({url: 'provinces', server: true})
                this.provinces = data.value
            }
            return this.provinces
        }
    }
})