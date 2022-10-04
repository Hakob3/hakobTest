import {createStore} from "vuex";
import {productModules} from "./productModules";

export default createStore({
    state: () => {
        return {
            isAuth: false
        }
    },
    modules: {
        products: productModules
    }
});