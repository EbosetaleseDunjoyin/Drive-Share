import { ref, computed, reactive } from "vue";
import { defineStore } from "pinia";

const getUserLocation = async () => {
  return new Promise((res,rej) => {
    navigator.geolocation.getCurrentPosition(res,rej);
  })
}

export const useLocationStore = defineStore("location", () => {
  const destination = reactive({
    name: "",
    address: "",
    geometry: {
        lat: null,
        lng: null
    },
  });
  const userLocation = reactive({
    geometry: {
        lat: null,
        lng: null
    },
  });

  const reset = () => {
     
     destination.name = "";
     destination.address = "";
     destination.geometry.lat = null;
     destination.geometry.lng = null;

     userLocation.geometry.lat = null;
     userLocation.geometry.lng = null;


  }

  const updateUsersLocation = async () => {
    const current = await getUserLocation();

    userLocation.geometry = {
      lat: current.coords.latitude,
      lng: current.coords.longitude,
    };
  }

  const doubleCount = computed(() => count.value * 2);
  function increment() {
    count.value++;
  }

  return { destination, userLocation, updateUsersLocation, reset };
});
