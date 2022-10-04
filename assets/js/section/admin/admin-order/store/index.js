import Vue from "vue";
import { createApp } from "vue";
import { createStore } from "vuex";
import products from "./modules/products";

const store = createStore({
    modules: {
        products
    },
    strict: debug
});

const debug = process.env.NODE_ENV !== "production";

export default store;