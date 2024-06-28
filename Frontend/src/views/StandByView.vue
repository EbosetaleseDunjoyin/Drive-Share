<template>
     <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-6 text-center">Waiting for ride requests...</h1>
        <div class="mt-8 flex justify-center">
            <Loader />
        </div>
    
  </div>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import Loader from '../components/Loader.vue';
import Echo from 'laravel-echo';
 
import Pusher from 'pusher-js';
// window.Pusher = Pusher;

onMounted(() => {

    let echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        wssPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });

    echo.channel('drivers')
        .listen('TripCreated', (e) => {
            console.log('TripCreated', e)
        })
})
 

const data = reactive({
    isLoading: false,
});


</script>

<style lang="scss" scoped>

</style>