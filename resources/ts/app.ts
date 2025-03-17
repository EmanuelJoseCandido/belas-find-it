import "./bootstrap";
import router from "./router";
import { createApp } from "vue";
import { createPinia } from 'pinia'
import app from "./app.vue";

const pinia = createPinia()
createApp(app).use(router).use(pinia).mount("#app");
