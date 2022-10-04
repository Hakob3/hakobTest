import Main from '../pages/Main'
import {createRouter, createWebHistory} from "vue-router";
import ProductPage from "../pages/ProductPage";
import GetProductPage from "../pages/GetProductPage";
import ProductPageWithStore from "../pages/ProductPageWithStore";
import ProductPageCompositionApi from "../pages/ProductPageCompositionApi";

const routes = [
    {
        path: '/vue/main',
        component: Main
    },
    {
        path: '/vue/products',
        component: ProductPage
    },
    {
        path: '/vue/products/:id',
        component: GetProductPage
    },
    {
        path: '/vue/store',
        component: ProductPageWithStore
    },
    {
        path: '/vue/composition',
        component: ProductPageCompositionApi
    },

]

const router = createRouter({
    routes,
    history: createWebHistory('')
});

export default router;