<template>
    <div class="card border border-light border-4 rounded shadow-sm my-3">
        <div class="card-body">
            <div class="container">
                <Navbar />
                <div className="row my-5">
                    <div className="col-md-4">
                        <ul className="list-group">
                            <li className="list-group-item border border-2 border-dark
                                text-center mb-2">
                                <i className="bi bi-person"></i> {{ authStore?.user?.name }}
                            </li>
                            <li className="list-group-item border border-2 border-dark
                                text-center mb-2">
                                <i className="bi bi-envelope"></i> {{ authStore?.user?.email }}
                            </li>
                            <li className="list-group-item border border-2 border-dark
                                text-center mb-2">
                                <i className="bi bi-heart-fill"></i> {{ authStore?.user?.number_of_hearts }}
                            </li>
                        </ul>
                    </div>
                    <div className="col-md-8 d-flex justify-content-between align-items-center"
                        v-if="authStore?.user?.subscriptions?.length"
                    >
                        <h5>
                            You are subscribed to 
                            {{
                                authStore.user.subscriptions.length > 1 ? 'plans' : 'plan'
                            }}
                        </h5>
                        <div className="d-flex flex-column">
                            <div 
                                v-for="subscription in authStore.user.subscriptions" 
                                :key="subscription.id">
                                <span className="badge bg-dark p-2 mx-2 mb-2">
                                    {{ subscription.plan.name }}
                                </span>
                                <span class="fw-bold me-2">
                                    Ends: {{ subscription.current_period_end }}
                                </span>
                                <button 
                                    @click="unsubscribe(subscription.stripe_subscription_id)"
                                    className="btn btn-danger rounded-0 me-1 mb-1"
                                    :disabled="data.loading"    
                                >
                                    <i className="bi bi-x-circle"></i> Unsubscribe
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import axios from "axios"
import { reactive } from "vue"
    import { useRouter } from "vue-router"
    import { useToast } from "vue-toastification"
    import { BASE_URL, headersConfig } from "../../helpers/config"
    import { useAuthStore } from "../../stores/useAuthStore"
    import Navbar from "../layouts/Navbar.vue"

    //define the store variable
    const authStore = useAuthStore()

    //define the router
    const router = useRouter()

    //define the toast
    const toast = useToast()

    //define data properties
    const data = reactive({
        loading: false
    })

    //unsubscribe the user from a plan
    const unsubscribe = async (stripe_subscription_id) => {
        data.loading = true
        try {
            const response = await axios.post(`${BASE_URL}/cancel`, 
            {
                stripe_subscription_id
            },
            headersConfig(authStore.access_token))

            //get response
            if (response.data.error) {
                toast.error(response.data.error, {
                    timeout: 2000,
                })
                data.loading = false
            } else {
                authStore.setUser(response.data.user)
                data.loading = false
                toast.success(response.data.message, {
                    timeout: 2000,
                })
            }
        } catch (error) {
            data.loading = false
            console.log(error)
        }
    }
</script>

<style scoped>
</style>