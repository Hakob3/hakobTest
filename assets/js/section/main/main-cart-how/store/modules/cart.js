import axios from "axios";
import {StatusCodes} from "http-status-codes";
import {apiConfig} from "../../../../admin/admin-order/store/settings/api-config";

function getAlertStructure() {
    return {
        type: null,
        message: null
    }
}

export const cart = {
    state: () => {
        return {
            cart: {},
            alert: getAlertStructure(),
            isSentForm: false,
            staticStore: {
                url: {
                    urlAssetImageProducts: window.staticStore.urlAssetImageProducts,
                },
            }
        }
    },
    getters: {
        totalPrice(state) {
            let result = 0;
            if (!state.cart.cartProducts) {
                return 0;
            }

            state.cart.cartProducts.forEach(
                cartProduct => {
                    result += cartProduct.product.price * cartProduct.quantity;
                })

            return result;
        }
    },
    actions: {
        async getCart({state, commit}) {
            const response = await axios.get('/api/carts', apiConfig);

            if (
                response.data &&
                response.data["hydra:member"].length &&
                response.status === StatusCodes.OK
            ) {
                commit('setCart', response.data["hydra:member"][0]);
            } else {
                commit('setAlert', {
                    type: 'info',
                    message: 'Your cart is empty ...'
                })
            }
        },

        async cleanCart({state, commit}) {
            const response = await axios.delete('/api/carts/' + state.cart.id, apiConfig);

            if (response.status === StatusCodes.NO_CONTENT) {
                commit('setCart', {});
            }
        },

        async removeCartProduct({state, commit, dispatch}, cartProductId) {
            const response = await axios.delete('/api/cart_products/' + cartProductId, apiConfig);

            if (response.status === StatusCodes.NO_CONTENT) {
                dispatch('getCart');
            }
        },
        async updateCartProductQuantity({state, dispatch}, payload) {
            const response = await axios.patch('/api/cart_products/' + payload.cartProductId,
                {
                    quantity: parseInt(payload.quantity)
                },
                {
                    headers: {
                        accept: "application/json",
                        "Content-Type": "application/merge-patch+json"
                    }
                });

            if (response.status === StatusCodes.OK) {
                dispatch('getCart')
            }
        },
        async makeOrder({state, commit, dispatch}) {
            const response = await axios.post('/api/orders',
                {
                    cartId: state.cart.id
                }, apiConfig);

            if (response.data && response.status === StatusCodes.CREATED) {
                commit('setAlert', {
                    type: 'success',
                    message: 'Thank you!!!😜'
                });
                commit('setIsSentForm', true);
                dispatch('cleanCart');
            }

        }
    },
    mutations: {
        setCart(state, cart) {
            state.cart = cart;
        },
        cleanAlert(state) {
            state.alert = getAlertStructure()
        },
        setAlert(state, model) {
            state.alert = {
                type: model.type,
                message: model.message
            };
        },
        setIsSentForm(state, value) {
            state.isSentForm = value;
        }
    },
    namespaced: true
}