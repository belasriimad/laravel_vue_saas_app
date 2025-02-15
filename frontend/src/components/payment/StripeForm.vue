<template>
    <div>
        <div ref="cardContainer" class="card"></div> 
        <button class="btn btn-sm btn-dark" 
            @click="subscribe"
            :disabled="loading === true"    
        >Subscribe</button>
    </div>
</template>

<script setup>
    //imports
    import { ref, onMounted } from 'vue'
    import { loadStripe } from '@stripe/stripe-js'
    import axios from 'axios'
    import { useAuthStore } from '../../stores/useAuthStore'
    import { BASE_URL, headersConfig } from '../../helpers/config'
    import { useToast } from 'vue-toastification'
    import { useRouter } from 'vue-router'

    //define store
    const authStore = useAuthStore()

    //define the router
    const router = useRouter()

    //define the toast
    const toast = useToast()

    //define variables we need
    const stripe = ref(null)
    const elements = ref(null)
    const card = ref(null)
    const cardContainer = ref(null)
    const loading = ref(false)

    onMounted(async () => {
        try {
            stripe.value = await loadStripe('pk_test_51C19VNGin0JfRTbQbj3b6ypR0xTxiBjmA9Sb6ybb7FoXf1QljY9ezAVvdG2vGXzCldfJVxvvx0JMCEEdAvRbD1wz00nlQVpTrG')
            elements.value = stripe.value.elements()
            
            card.value = elements.value.create('card')
            
            if (cardContainer.value) {
                card.value.mount(cardContainer.value) // Mounts the card input
            } else {
                console.error('Something went wrong try again.')
            }
        } catch (error) {
            console.error('Stripe initialization failed:', error)
        }
    })

    const subscribe = async () => {
        if (!stripe.value || !card.value) {
            console.error('Stripe has not been initialized properly.')
            return
        }

        const { paymentMethod, error } = await stripe.value.createPaymentMethod({
            type: 'card',
            card: card.value,
        })

        if (error) {
            console.error('Payment method creation failed:', error)
            return
        }

        loading.value = true

        //send request
        try {
            const response = await axios.post(`${BASE_URL}/subscribe`, 
            {
                plan_id: authStore.chosenPlan.id,
                payment_method: paymentMethod.id,
                price_id: authStore.chosenPlan.price_id,
            },
            headersConfig(authStore.access_token))
            //get response
            if (response.data.error) {
                toast.error(response.data.error, {
                    timeout: 2000,
                })
                loading.value = false
            } else {
                authStore.setChosenPlan(null)
                toast.success(response.data.message, {
                    timeout: 2000,
                })
                loading.value = false
                router.push("/")
            }
        } catch (error) {
            loading.value = false
            console.log(error)
        }
           
    }
</script>

<style>
    .input {
        display: block;
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
    }
    .card {
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>
