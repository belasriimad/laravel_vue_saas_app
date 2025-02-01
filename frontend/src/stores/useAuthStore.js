import axios from 'axios'
import { defineStore } from 'pinia'
import { BASE_URL, headersConfig } from '../helpers/config'

export const useAuthStore = defineStore('auth', {
    state: () => ({ 
        isLoggedIn: false,
        user: null,
        access_token: '',
        validationErrors: null,
        isLoading: false,
        chosenPlan: null
    }),
    persist: true,
    actions: {
        setIsLoggedIn() {
            this.isLoggedIn = true
        },
        setUser(user) {
            this.user = user
        },
        setToken(token) {
            this.access_token = token
        },
        clearAuthData() {
            this.isLoggedIn = false
            this.user = null
            this.access_token = ''
        },
        setValidationErrors(errors) {
            this.validationErrors = errors
        },
        clearValidationErrors() {
            this.validationErrors = null
        },
        async decrementUserHearts() {
            try {
                const response = await axios.get(`${BASE_URL}/user/decrement/hearts`,
                    headersConfig(this.access_token))
                this.user = response.data.user
            } catch (error) {
                console.log(error)
            }
        },
        setChosenPlan(plan) {
            this.chosenPlan = plan
        },
    }
})