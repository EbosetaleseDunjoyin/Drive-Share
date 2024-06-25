import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import Notify from "simple-notify";
import VueGoogleMaps from "@fawmi/vue-google-maps";

import "simple-notify/dist/simple-notify.css";


const app = createApp(App);

app.use(createPinia())
app.use(router)
app.use(VueGoogleMaps, {
  load: {
    key: "AIzaSyCnRsYniTz69FOV522Mtl6vk5BSPMkHI0E",
    libraries: "places",
  },
})
app.config.globalProperties.$notify = function (options) {
  const defaultOptions = {
    status: "success",
    title: "",
    text: "Notify text lorem ipsum",
    effect: "slide",
    speed: 2000,
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 5000,
    gap: 100,
    distance: 20,
    type: 1,
    position: "right top",
  };

  const mergedOptions = Object.assign({}, defaultOptions, options);
  new Notify(mergedOptions);
};
app.mount('#app')
