<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-6 text-center" v-if="trip.trip.status !== 'completed' "> {{ data.status }}</h1>
        <div class="" >
             <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left" v-if="trip.trip.status!== 'completed' ">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    
                    <div class="my-3">
                          <GMapMap
                                :center="location.userLocation.geometry"
                                :zoom="10"
                                class="h-80 w-full"
                                ref="gMap"
                            >
                                <GMapMarker :position="location.userLocation.geometry" :icon="currentIcon" class="w-2 h-auto"/>
                                <GMapMarker :position="location.destination.geometry" :icon="destinationIcon" />
                          </GMapMap>
                        
                    </div>
                    <p class="text-xl mb-2">Going to <strong>{{ location.destination.name }}</strong></p>
                </div>
                <div class="flex justify-end bg-gray-50 dark:bg-black-soft px-4 py-3  sm:px-6">
                    <button
                        @click="handleTripEnd"
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        v-if="trip.trip.status === 'started'"
                        >
                        <span v-if="!data.isLoading">Complete Trip</span>
                        <div role="status" v-else>
                            <svg aria-hidden="true" class="w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                            </svg>
                            <span class="sr-only">Loading...</span>
                        </div>
                    </button>
                    <button
                        @click="handleTripStart"
                        class=" bg-black dark:bg-white dark:text-black  px-3 inline-fex justify-center rounded-md border border-transparent py-2"
                        :disabled="data.isLoading"
                        v-if="trip.trip.status === 'pending'"
                        >
                        <span v-if="!data.isLoading">Passenger Picked Up</span>
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
             <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left" v-if="trip.trip.status === 'completed' ">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    <p class="text-xl mb-2 text-center">Trip Completed!!!!</p>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, getCurrentInstance } from 'vue';
import { useTripStore } from '@/stores/trip'
import { useLocationStore } from '@/stores/location';
import { useRouter } from 'vue-router';
import http from '@/helpers/http';
// window.Pusher = Pusher;
const notify = getCurrentInstance();
const instance =  notify.appContext.config.globalProperties;
const trip = useTripStore();
const location = useLocationStore();
const router = useRouter();

const gMap = ref(null);
const interverRef = ref(null)
const data = reactive({
    isLoading: false,
    status : "Driving to passenger..."
});

onMounted(() => {
    if(location.destination.name === ""){
         router.push({
            name:"standBy"
        })
    };
    gMap.value.$mapPromise.then((mapObject) => {
        updateMapBounds(mapObject);

        interverRef.value = setInterval(async () => {
            await location.updateUsersLocation();
            broadcastDriverLocation()
             updateMapBounds(mapObject);

        },5000)

    })
});

onUnmounted(() => {
    clearInterval(interverRef.value);
    interverRef.value = null;
})

const broadcastDriverLocation = () =>{
    http().post(`/api/v1/trip/${trip.trip.id}/driverLocation`,{
        driver_location : location.userLocation.geometry
    })
    .then((response) => {
       

    }).catch((error) => {
        console.error(error);
    });
}

const updateMapBounds = (mapObject) => {
     let originPoint = new google.maps.LatLng(trip.trip.origin),
         destinationPoint = new google.maps.LatLng(trip.trip.destination),
         latlngBounds = new google.maps.LatLngBounds();

         latlngBounds.extend(originPoint);
         latlngBounds.extend(destinationPoint);

         mapObject.fitBounds(latlngBounds);
}

const currentIcon = {
    url : "https://openmoji.org/data/color/svg/1F698.svg",
    scaledSize: {
        width: 32,
        height: 32
    }
}
const destinationIcon = {
    url : "https://openmoji.org/data/color/svg/1F9D1.svg",
    scaledSize: {
        width: 32,
        height: 32
    }
}

const handleTripStart = () => {
    data.isLoading =  true
     http().post(`/api/v1/trip/${trip.trip.id}/status`,{
        status : "started"
    })
    .then((response) => {
       data.status = "Traveling to destination..."
       location.$patch({
        destination: {
            name: response.data.data.destination_name,
            geometry: response.data.data.destination
        }
       });
       trip.$patch({trip : response.data.data});

    }).catch((error) => {
        console.error(error);
    });
    data.isLoading = false

}
const handleTripEnd = () => {
    data.isLoading =  true
    http().post(`/api/v1/trip/${trip.trip.id}/status`,{
        status : "completed"
    })
    .then((response) => {
       data.status = "Trip Completed"
        trip.$patch({ trip: response.data.data });

       setTimeout(() => {

           trip.reset();
           location.reset();

           router.push({
            name : "standBy"
           })
       }, 5000)

    }).catch((error) => {
        console.error(error);
    });
    data.isLoading = false
}





</script>