import axios from "axios";
import {StatusCodes} from "http-status-codes";
import {apiConfig} from "../../../../admin/admin-order/store/settings/api-config";

export const cart = {
    state: () => {
        return {
            cart: {},
            staticStore: {
                url: {
                    urlAssetImageProducts: window.staticStore.urlAssetImageProducts,
                },
            }
        }
    },
    // getters: {},
    actions: {
        async getCart({state, commit}) {
            const response = await axios.get('/api/carts', apiConfig);

            if (response.data && response.status === StatusCodes.OK) {
                commit('setCart', response.data["hydra:member"][0]);
            }
        },

        async removeCartProduct({state, dispatch}, cartProductId) {
            const response = await axios.delete('/api/cart_products/' + cartProductId, apiConfig);

            if (response.status === StatusCodes.NO_CONTENT) {
                dispatch('getCart')
            }
        }
    },
    mutations: {
        setCart(state, cart) {
            state.cart = cart;
        }
    },
    namespaced: true
}