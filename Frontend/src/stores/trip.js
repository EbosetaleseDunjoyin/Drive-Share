import { ref, computed, reactive } from "vue";
import { defineStore } from "pinia";



export const useTripStore = defineStore("trip", () => {
    const trip = reactive({
        id: null,
        user_id: null,
        origin: {
            lat: null,
            lng: null,
        },
        
        destination_name:"",

        destination: {
            lat: null,
            lng: null,
        }

    });

    const reset = () => {
        trip.id = null;
        trip.user_id = null;
        trip.origin.lat = null;
        trip.origin.lng = null;
        trip.destination_name = "";
        trip.destination.lat = null;
        trip.destination.lng = null;
    };

    return { trip, reset };
});
