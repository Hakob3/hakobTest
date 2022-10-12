import axios from "axios";
import {StatusCodes} from "http-status-codes";

export const products = {
    state: () => {
        return {
            categories: [],
            staticStore: {
                orderProducts: window.staticStore.orderProducts,
            }
        }
    },
    getters: {},
    actions: {
        async removeOrderProduct({state, dispatch}, orderProductId) {

            const result = await axios.delete("/api/order_products/" + orderProductId,
                {
                    headers: {
                        accept: "application/ld+json",
                        "Content-Type": "application/json"
                    }
                });

            if (result.status === StatusCodes.NO_CONTENT){
                console.log('deleted');
            }
        }
    },
    mutation: {},
    namespaced: true
}