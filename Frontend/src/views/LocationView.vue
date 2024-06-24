<template>
    <div class="pt-16">

        <h1 class="text-3xl font-semibold mb-6 text-center">Where are we going </h1>
       <form action="#" method="POST" @submit.prevent="">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    <div class="mb-3">
                        <input type="text" name="location"  v-model="data.location" id="location" placeholder="808 121"
                        class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-black-soft px-4 py-3 text-right sm:px-6">
                    <button
                        type="submit"
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        >
                        Find A Ride
                    </button>
                </div>
            </div>
        </form>
    
  </div>
</template>

<script setup>
import { reactive } from "vue";


const data = reactive({
    isLoading : false,
    location: ""
});

</script>

<style lang="scss" scoped>

</style>