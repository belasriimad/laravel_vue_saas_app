<template>
    <div class="card border border-light border-4 rounded shadow-sm my-3">
        <Spinner :store="data.store" />
        <div class="card-body">
            <div class="container">
                <Navbar />
                <div className="row">
                    <div className="col-md-12">
                        <div className="row my-5">
                            <div className="col-md-4" 
                                v-for="plan in data.plans" 
                                :key="plan.id"
                            >
                                <div className="card border border-dark border-2 bg-white shadow">
                                    <div className="card-header border-bottom 
                                        border-dark border-2 fw-bold text-center bg-white">
                                        {{ plan.name }}   
                                    </div>
                                    <div className="card-body">
                                        <div className="d-flex flex-column justify-content-center align-items-center">
                                            <div className="d-flex justify-content-center align-items-center">
                                                <span className="fw-bold">$</span>
                                                <h1> {{ plan.price }} </h1>
                                                <span className="text-danger fw-bold mx-1">/</span>
                                                Month
                                            </div>
                                            <div>
                                                <span className="fw-bold">
                                                     {{ plan.number_of_hearts }} <i className="text-danger bi bi-heart-fill"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="card-footer border-top border-dark border-2 bg-white text-center">
                                        <button className="btn btn-danger rounded-0"
                                            @click="authStore.setChosenPlan(plan)"
                                        >
                                            <i className="bi bi-check2-circle me-1"></i>
                                            Choose a plan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="row mt-3" v-if="authStore?.chosenPlan">
                    <div className="col-md-6 mx-auto">
                        <div className="card bg-light">
                            <div className="card-body">
                                <!-- stripe subscription form  -->
                                <StripeForm />
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
    import { onMounted, onUnmounted, reactive } from "vue"
    import { useRouter } from "vue-router"
    import { BASE_URL, headersConfig } from "../../helpers/config"
    import { useAuthStore } from "../../stores/useAuthStore"
    import Navbar from "../layouts/Navbar.vue"
    import Spinner from "../layouts/Spinner.vue"
    import StripeForm  from "../payment/StripeForm.vue"

    //define store
    const authStore = useAuthStore()

    //define the router
    const router = useRouter()

    //define data properties
    const data = reactive({
        plans: [],
        store: {
            isLoading: false
        }
    })

    //get plans 
    const getPlans = async () => {
        data.store.isLoading = true
        try {
            const response = await axios.get(`${BASE_URL}/plans`,
                headersConfig(authStore.access_token))
            data.plans = response.data.plans
            data.store.isLoading = false
        } catch (error) {
            data.store.isLoading = false
            console.log(error)
        }
    }

    //get all plans once the component is mounted
    onMounted(() => {
        if(authStore?.user?.number_of_hearts > 0) {
            router.push('/')
        }else {
            getPlans()
        }
    })

    onUnmounted(() => authStore.setChosenPlan(null))
</script>

<style scoped>
</style>