<template>
    <div class="pt-16">

        <h1 class="text-3xl font-semibold mb-6 text-center">Waiting on a driver... </h1>
       <form action="#" method="POST" @submit.prevent="">
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
                <div class="bg-white dark:bg-black-soft px-4 py-5 sm:p-6">
                    
                    <div class="my-3">
                          <GMapMap
                                :center="location.destination.geometry"
                                :zoom="10"
                                class="h-80 w-full"
                                ref="gMap"
                            >
                                <GMapMarker :position="location.destination.geometry" />
                          </GMapMap>
                        
                    </div>
                    <p class="text-sm mb-2">When a driver accepts the trip, their info will appear here</p>
                </div>
                
            </div>
        </form>
    
  </div>
</template>

<script setup>
import { ref,reactive, onMounted } from "vue";
import { useLocationStore } from "@/stores/location";
import { useRouter } from "vue-router";
import axios from "axios";




const location = useLocationStore();
const router = useRouter();
const data = reactive({
    isLoading : false,
});

const gMap = ref(null);


onMounted( async () => {
    if(location.destination.name === ""){
         router.push({
            name:"location"
        })
    };

    //Get User Location
    // navigator.geolocation.getCurrentPosition((success) => {
    //     console.log(success);
    // }, (error) => {
    //     console.log(error);
    // });
    await location.updateUsersLocation();

    gMap.value.$mapPromise.then((mapObject) => {
        let currentPoint = new google.maps.LatLng(location.userLocation.geometry),
         destinationPoint = new google.maps.LatLng(location.destination.geometry);

        let directionService = new google.maps.DirectionsService,
        directionDisplay = new google.maps.DirectionsRenderer({
            map: mapObject
        });

        directionService.route({
            origin: currentPoint,
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
    })
})


// console.log(data.destination);


const handleConfirmTrip = () => {
    data.isLoading = true;
    if(location.destination.name != ""){
        router.push({
            name:"location"
        })
    }

    axios.post("http://127.0.0.1:8000/api/v1/trip",{
        origin: location.userLocation.geometry ,
        destination: location.destination.geometry  ,
        destination_name: location.destination.name,
    }).then((response) => {

        data.isLoading = false;
        router.push({
            name:"trip"
        })
    }).catch((error) => {

    })
}

</script>

<style lang="scss" scoped>

</style>