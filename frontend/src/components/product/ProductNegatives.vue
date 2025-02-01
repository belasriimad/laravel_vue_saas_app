<template>
    <div v-if="productsStore.product.negatives.length">
        <h5>
            Negatives
        </h5>
        <ul>
            <li class="flex flex-column" 
                v-for="negative in productsStore.product.negatives"
                :key="negative.id">
            <strong>{{ negative.name }}</strong>
            <p>
                {{ negative.description }}
            </p>
            </li>
        </ul>
    </div>
</template>

<script setup>
  import { useProductStore } from "../../stores/useProductStore"

  //define the store variable
  const productsStore = useProductStore()
</script>

<style scoped>
</style>