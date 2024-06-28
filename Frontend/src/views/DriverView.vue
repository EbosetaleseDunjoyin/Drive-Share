<template>
     <div class="pt-16">
        <div class="" >
            <h1 class="text-3xl font-semibold mb-4">Driver and car details</h1>
            <form action="#" method="POST" @submit.prevent="handleDriverCarDetails">
                <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                    <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                        <div class="mb-3">
                            <input type="number" name="year" v-model="data.year"  placeholder="2000 - 2024"
                            class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="make" v-model="data.make" id="make" placeholder="Toyota"
                            class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="model" v-model="data.model" id="model" placeholder="Camry"
                            class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="color" v-model="data.color" id="color" placeholder="Black"
                            class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="license_plate" v-model="data.license_plate" id="license_plate" placeholder="ABJ 12345"
                            class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                        </div>
                       
                    </div>
                    <div class="bg-gray-50 dark:bg-black-soft px-4 py-3 text-right sm:px-6">
                        <button
                            type="submit"
                            class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                            :disabled="data.isLoading"
                            >
                            <span v-if="!data.isLoading">Continue</span>
                            <div role="status" v-else>
                                <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                </svg>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { reactive, getCurrentInstance, onMounted } from 'vue';
import { useRouter } from "vue-router";
import http from "@/helpers/http";

const router = useRouter()
const notify = getCurrentInstance();
const instance =  notify.appContext.config.globalProperties;

const data = reactive({
    isLoading : false,
    year : null,
    make: "",
    model:"",
    color:"",
    license_plate:"",

});

onMounted(() => {
     http().get('/api/v1/driver')
        .then((response)=>{
            // console.log(response);
            if(response.data.data.user.driver){
                router.push({
                    name:'standBy'
                })
            }
        }).catch((error) => {
            console.error(error);
        })
});

const handleDriverCarDetails = () => {
    data.isLoading = true;
    http().post('/api/v1/driver',data)
        .then((response)=>{
            console.log(response);
            instance.$notify({status: "success",title: 'Success', text: response.data.message});
            router.push({
                name:'standBy'
            })
          
        }).catch((error) => {
            instance.$notify({status: "error",title: 'Error occured', text: error.response?.data.message});
            console.error(error);
        })

}
</script>, onMountedimport { useRouter } from 'vue-router';
