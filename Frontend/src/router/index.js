import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import LoginView from '../views/LoginView.vue';
import OnBoardingView from '../views/OnBoardingView.vue';
import LocationView from "../views/LocationView.vue";
import axios from "axios";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
      meta: {
        // title: "Home",
      },
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
      meta: {
        title: "Login",
      },
    },
    {
      path: "/onboarding",
      name: "onboarding",
      component: OnBoardingView,
      meta: {
        requiresAuth: true,
        title: "OnBoarding",
      },
    },
    {
      path: "/location",
      name: "location",
      component: LocationView,
      meta: {
        requiresAuth: true,
        title: "Location",
      },
    },
  ],
});
router.beforeEach((to, from, next) => {
  setTitleAndDesc(to);
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem("app-token");
    if (!token) {
      // User is not authenticated, redirect to login
      next("/login");
    }
    checkTokenAuthenticity()
    next();
  } else{
    next();
  }
});

const checkTokenAuthenticity = () => {
  const token = localStorage.getItem("app-token");
  axios
    .get("http://127.0.0.1:8000/api/user", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })
    .then((response) => {})
    .catch((error) => {
      localStorage.removeItem("app-token");
      router.push("/login");
    });
}

const  setTitleAndDesc = (to) => {
  const { title, description } = to.meta;
  const defaultTitle = 'Drive Share';
  const defaultDescription = 'This is an app thet helps user get to any where';
  document.title = title ? `${title} | ${defaultTitle}` : defaultTitle;
  const descriptionElement = document.querySelector('head meta[name="description"]')
  descriptionElement.setAttribute('content', description || defaultDescription);
}

export default router