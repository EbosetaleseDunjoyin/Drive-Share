<template>
    <div class="pt-16">

        <h1 class="text-3xl font-semibold mb-6 text-center">{{ data.status }}</h1>
       <form action="#" method="POST" @submit.prevent="">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    
                    <div class="my-3">
                          <GMapMap
                                :center="location.userLocation.geometry"
                                :zoom="10"
                                class="h-80 w-full"
                                ref="gMap"
                            >
                                <GMapMarker :position="location.userLocation.geometry" :icon="currentIcon" />
                                <GMapMarker :position="trip.trip.driver_location" :icon="driverIcon" v-if="trip.trip.driver_location !== null" />
                          </GMapMap>
                        
                    </div>
                    <p class="text-sm mb-2">{{ data.message }}</p>
                </div>
                
            </div>
        </form>
    
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, getCurrentInstance } from 'vue';
import { useTripStore } from '@/stores/trip'
import { useLocationStore } from '@/stores/location';
import { useRouter } from 'vue-router';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import http from '@/helpers/http';



const location = useLocationStore();
const trip = useTripStore();
const router = useRouter();
const data = reactive({
    isLoading : false,
    message: "When a driver accepts the trip, their info will appear here",
    status: "Waiting on a driver..."
});

const gMap = ref(null);
const interverRef = ref(null)


onMounted( async () => {
    if(location.destination.name === ""){
         router.push({
            name:"location"
        })
    };

   
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

     echo.channel(`passenger_${trip.trip.user_id}`)
        .listen('TripAccepted', (e) => {
            // console.log('TripAccepted', e);
            trip.$patch({trip : e.trip});
            data.status = "Driver is on his way";
            data.message = `${e.trip.driver.user.name} is coming in a ${e.trip.year} ${e.trip.driver.color} ${e.trip.driver.make} ${e.trip.driver.model} with license plate ${e.trip.driver.license_plate}`;
            // setTimeout(initMapDirections, 3000);
        }).listen('TripLocationChanged', (e) => {
             trip.$patch({trip : e.trip});

             location.$patch({
                userLocation:{
                    geometry: e.trip.destination
                }
             })
        })
        .listen('TripStatusChanged', (e) => {
             trip.$patch({trip : e.trip});

            
             if(e.trip.status === "started"){
                 data.status = "You are on your way";
                 data.message = `You're headed to ${e.trip.destination_name}`;
             }else if(e.trip.status === "completed"){
                data.status = "You've arrived";
                 data.message = `Hope you enjoyed your ride with ${e.trip.driver.user.name}`;

                setTimeout(() => {

                    trip.reset();
                    location.reset();

                    router.push({
                        name : "onboarding"
                    })
                }, 12000)
             }
        })
        ;
        console.log(trip.trip.driver_location)
        // console.log(trip.trip.driver_location)
        if(trip.trip.driver_location){

            gMap.value.$mapPromise.then((mapObject) => {
               updateMapBounds(mapObject);
       
               interverRef.value = setInterval(async () => {
                   await location.updateUsersLocation();
                   broadcastDriverLocation()
                    updateMapBounds(mapObject);
       
               },5000)
       
           })
        }


})

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

const currentIcon = {
    url : "https://openmoji.org/data/color/svg/1F9D1.svg",
    scaledSize: {
        width: 32,
        height: 32
    }
}

const driverIcon = {
    url : "https://openmoji.org/data/color/svg/1F698.svg",
    scaledSize: {
        width: 32,
        height: 32
    }
}

const updateMapBounds = (mapObject) => {
     let originPoint = new google.maps.LatLng(trip.trip.driver_location),
         destinationPoint = new google.maps.LatLng(location.userLocation.geometry),
         latlngBounds = new google.maps.LatLngBounds();

         latlngBounds.extend(originPoint);
         latlngBounds.extend(destinationPoint);

         mapObject.fitBounds(latlngBounds);
}
</script>

<style lang="scss" scoped>

</style>