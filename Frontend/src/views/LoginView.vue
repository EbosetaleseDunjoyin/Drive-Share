<template>
  <div class="pt-16">
    <div class="" v-if="!data.verificationReady">
        <h1 class="text-3xl font-semibold mb-4">Login</h1>
        <form action="#" method="POST" @submit.prevent="handleLogin">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    <div class="mb-3">
                        <input type="text" name="name" v-model="data.name" id="name" placeholder="John Doe"
                        class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="phone" v-maska data-maska="+234 ### ### ####" v-model="data.phone" id="phone" placeholder="+ (234) 808 121 4126"
                        class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-black-soft px-4 py-3 text-right sm:px-6">
                    <button
                        type="submit"
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        >
                        Continue
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="" v-else>
        <h1 class="text-3xl font-semibold mb-4">Insert Login Code</h1>
        <form action="#" method="POST" @submit.prevent="handleVerification">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    <div class="mb-3">
                        <input type="text" name="phone" v-maska data-maska="### ###" v-model="data.loginCode" id="phone" placeholder="808 121"
                        class="mt-1  block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm text-black"/>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-black-soft px-4 py-3 text-right sm:px-6">
                    <button
                        type="submit"
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        >
                        Verify
                    </button>
                </div>
            </div>
        </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, getCurrentInstance, onMounted, computed  } from 'vue';
import { vMaska } from "maska/vue"
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const notify = getCurrentInstance();
const instance =  notify.appContext.config.globalProperties;

onMounted(() => {
    if(localStorage.getItem('app-token')) {router.push({name:"onboarding"});}
});

const data = reactive({
    phone: null,
    name: "",
    loginCode:null,
    isLoading: false,
    verificationReady: false
});



const handleLogin = () =>{
    data.isLoading = true;
    axios.post("http://127.0.0.1:8000/api/v1/login", 
        {
            name: data.name,
            phone: parseInt(data.phone.replace(/\D/g, ''), 10),
           
        }
    )
    .then((response)=>{
        console.log(response.data)
        instance.$notify({status: "success",title: 'Success', text: response.data.message});
         data.isLoading = false;
         data.verificationReady = true;
    })
    .catch((error) => {
        console.error(error);
         instance.$notify({status: "error",title: 'Error occured', text: error.response.data.message});
         data.isLoading = false;
         
    })
    
}
const handleVerification = () =>{
    data.isLoading = true;
    axios.post("http://127.0.0.1:8000/api/v1/login/verify", 
        {
            phone: parseInt(data.phone.replace(/\D/g, ''), 10),
            login_code: parseInt(data.loginCode.replace(/\D/g, ''), 10)
        }
    )
    .then((response)=>{
        console.log(response.data)
         data.isLoading = false;
         instance.$notify({status: "success",title: 'Success', text: response.data.message});
         localStorage.setItem("app-token",response.data.data.token);
         router.push({name:"onboarding"});
    })
    .catch((error) => {
        console.error(error);
         data.isLoading = false;
         instance.$notify({status: "error",title: 'Error occured', text: error.response.data.message});
    })
}

</script>


<style >

</style>
