<template>
    <div class="pt-16">

        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
            <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6 sm:py-12 ">
                <h1 class="text-3xl font-semibold mb-6 text-center">Welcome</h1>
                <div class="flex justify-between ">
                    <button
                       
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        >
                        Start Driving
                    </button>
                    <button
                       
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        @click="handleFindARide"
                        >
                        Find A Ride
                    </button>
                </div>
            </div>
        </div>
    
  </div>
</template>

<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";

const router = useRouter()

const data = reactive({
    isLoading : false
});

const handleFindARide = ()=>{
    router.push({
        name:"location"
    })
}



</script>

<style lang="scss" scoped>

</style>