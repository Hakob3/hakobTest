// import Vue from "vue";
import { createApp } from "vue";
import App from "./App";
import store from "./store";

// new Vue({
//     el: "#app",
//     render: h => h(App)
// })

const app = createApp(App);
app.mount('#app');
// app.use(store);