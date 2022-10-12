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
    actions: {},
    mutation: {},
    namespaced: true
}