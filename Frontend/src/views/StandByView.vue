<template>
     <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-6 text-center">{{ data.status }}</h1>
        <div class="mt-8 flex justify-center" v-if="!trip.trip.id">
            <Loader />
        </div>
        <div class="" v-else>
             <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    
                    <div class="my-3">
                          <GMapMap
                                :center="trip.trip.destination"
                                :zoom="10"
                                class="h-80 w-full"
                                ref="gMap"
                            >
                                <GMapMarker :position="trip.trip.destination" />
                          </GMapMap>
                        
                    </div>
                    <p class="text-xl mb-2">Going to <strong>{{ trip.trip.destination_name }}</strong></p>
                </div>
                <div class="flex justify-between bg-gray-50 dark:bg-black-soft px-4 py-3  sm:px-6">
                    <button
                        @click="handleDeclineTrip"
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        >
                        <span v-if="!data.isLoading">Decline</span>
                        <div role="status" v-else>
                            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                    <button
                        @click="handleAcceptTrip"
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        >
                        <span v-if="!data.isLoading">Accept</span>
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
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, getCurrentInstance } from 'vue';
import { useTripStore } from '@/stores/trip'
import { useLocationStore } from '@/stores/location';
import { useRouter } from 'vue-router';
import Loader from '../components/Loader.vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import http from '@/helpers/http';
// window.Pusher = Pusher;
const notify = getCurrentInstance();
const instance =  notify.appContext.config.globalProperties;
const trip = useTripStore();
const location = useLocationStore();
const router = useRouter();
const data = reactive({
    isLoading: false,
    status : "Waiting for ride request.."
});

const gMap = ref(null);

onMounted(async () => {

    await location.updateUsersLocation();
    let pusher = Pusher;

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
            // console.log('TripCreated', e);
            data.status = "Trip Requested";
            trip.$patch({trip : e.trip});
            setTimeout(initMapDirections, 3000);
        })
})
 



const initMapDirections = () => {
    // console.log(gMap.value)
    gMap.value.$mapPromise.then((mapObject) => {
        let originPoint = new google.maps.LatLng(trip.trip.origin),
         destinationPoint = new google.maps.LatLng(trip.trip.destination);

        let directionService = new google.maps.DirectionsService,
        directionDisplay = new google.maps.DirectionsRenderer({
            map: mapObject
        });

        directionService.route({
            origin: originPoint,
            destination: destinationPoint,
            avoidTolls: false,
            avoidHighways: false,
            travelMode: google.maps.TravelMode.DRIVING
        },(res,status) => {
            if(status === google.maps.DirectionsStatus.OK){
                directionDisplay.setDirections(res);
            }else{
                console.error(status)
            }
        })
    });
}

const handleAcceptTrip = () => {
    http().post(`/api/v1/trip/${trip.trip.id}/accept`,{
        driver_location : location.userLocation.geometry
    })
    .then((response) => {
        location.$patch({
            destination: {
                name: "Passenger",
                geometry: response.data.data.origin
            }
        });

        router.push({
            name:"driving"
        });

    }).catch((error) => {
        console.error(error);
    });
}
const handleDeclineTrip = () => {
    trip.reset();
    data.status = "Waiting for ride request.."
    instance.$notify({status: "success",title: 'Success', text: "Trip Rejected!"});
}




</script>

<style lang="scss" scoped>

</style>