import axios from "axios";
import {StatusCodes} from "http-status-codes";
import {apiConfig} from "../settings/api-config";

export const products = {
    state: () => {
        return {
            categories: [],
            orderProducts: [],
            categoryProducts: [],
            busyProductsIds: [],
            newOrderProduct: {
                categoryId: "",
                product: "",
                quantity: "",
                pricePerOne: "",
            },
            staticStore: {
                orderId: window.staticStore.orderId,
            },
            viewProductCountLimit: 30
        }
    },
    getters: {
        freeCategoryProducts(state) {
            return state.categoryProducts.filter(
                item => state.busyProductsIds.indexOf(item.id) === -1
            );
        },
    },
    actions: {
        async getOrderProducts({state, commit}) {
            const response = await axios.get('/api/orders/' + state.staticStore.orderId, apiConfig);

            console.log(response);
            if (response.status === StatusCodes.OK) {
                commit('setOrderProducts', response.data.orderProducts);
                commit('setBusyProductsIds');
            }
        },
        async getCategories({state, commit}) {
            const response = await axios.get("/api/categories/", apiConfig);

            if (response.data && response.status === StatusCodes.OK) {
                commit('setCategories', response.data['hydra:member']);
            }
        },

        async removeOrderProduct({state, dispatch}, orderProductId) {
            const response = await axios.delete("/api/order_products/" + orderProductId, apiConfig);

            if (response.status === StatusCodes.NO_CONTENT) {
                dispatch('getOrderProducts');
            }
        },

        async getProductsByCategory({state, commit}) {
            const response = await axios.get('/api/products',
                {
                    headers: apiConfig.headers,
                    params: {
                        page: 1,
                        itemsPerPage: state.viewProductCountLimit,
                        isPublished: true,
                        category: "/api/categories/" + state.newOrderProduct.categoryId
                    }
                })
            if (response.status === StatusCodes.OK) {
                commit('setCategoryProducts', response.data);
            }
        },
        async addNewOrderProduct({state, dispatch}) {

            const response = await axios.post('/api/order_products',
                {
                    pricePerOne: state.newOrderProduct.pricePerOne.toString(),
                    quantity: parseInt(state.newOrderProduct.quantity),
                    product: "/api/products/" + state.newOrderProduct.product,
                    appOrder: "/api/orders/" + state.staticStore.orderId
                },
                apiConfig
            );

            if (response.status === StatusCodes.CREATED) {
                dispatch('getOrderProducts');
            }
        }
    },
    mutations: {
        setOrderProducts(state, orderProducts) {
            state.orderProducts = orderProducts;
        },
        setCategories(state, categories) {
            state.categories = categories;
        },

        setNewProductData(state, formData) {
            state.newOrderProduct.categoryId = formData.categoryId;
            state.newOrderProduct.product = formData.product;
            state.newOrderProduct.quantity = formData.quantity;
            state.newOrderProduct.pricePerOne = formData.pricePerOne;
        },
        setCategoryProducts(state, categoryProducts) {
            state.categoryProducts = categoryProducts
        },
        setBusyProductsIds(state) {
            state.busyProductsIds = state.orderProducts.map(item => item.product.id)
        }
    },
    namespaced: true
}