<template>
    <div v-if="productsStore.product.positives.length">
        <h5>
            Positives
        </h5>
        <ul>
            <li class="flex flex-column" 
                v-for="positive in productsStore.product.positives"
                :key="positive.id">
            <strong>{{ positive.name }}</strong>
            <p>
                {{ positive.description }}
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