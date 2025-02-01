<template>
    <div class="card mb-3">
        <div class="card-body">
            <form class="row">
                <div class="col-md-11 mx-auto">
                    <input 
                        type="text" 
                        v-model="data.name"
                        @change="searchForProductByName"
                        :disabled="authStore?.user?.number_of_hearts < 1"
                        class="form-control" 
                        placeholder="Search">
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    import { reactive } from "vue";
    import { useAuthStore } from "../../stores/useAuthStore";
    import { useProductStore } from "../../stores/useProductStore"

    //define the store variable
    const productsStore = useProductStore()
    const authStore = useAuthStore()

    //define data properties
    const data = reactive({
        name: ''
    })

    //find product by name function
    const searchForProductByName = async () => {
        productsStore.fetchProductByName(data.name)
        data.name = ''
    }
</script>

<style scoped></style>
