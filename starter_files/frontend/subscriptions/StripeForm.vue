<template>
    <div>
        <div ref="cardContainer" class="card"></div> 
        <button 
            class="btn btn-sm btn-dark" 
        >Subscribe</button>
    </div>
</template>

<script setup>
    //imports
    import { ref, onMounted } from 'vue'
    import { loadStripe } from '@stripe/stripe-js'

    //define variables we need
    const stripe = ref(null)
    const elements = ref(null)
    const card = ref(null)
    const cardContainer = ref(null)

    onMounted(async () => {
        try {
            stripe.value = await loadStripe('YOUR STRIPE PUBLISHABLE KEY HERE')
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

        //send request here
           
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
