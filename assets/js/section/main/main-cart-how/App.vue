<template>
  <div class="row">
    <div class="col-lg-12 order-block">
      <div class="order-content">
        <alert/>
        <div v-if="showCartContent">
          <cart-product-list/>
          <cart-total-price/>
          <a class="btn btn-success mb-3 text-white"
             @click="makeOrder"
          >
            MAKE ORDER
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CartProductList from "./components/CartProductList";
import CartTotalPrice from "./components/CartTotalPrice";
import {mapActions, mapState} from "vuex";
import Alert from "./components/Alert";

export default {
  name: "App",
  components: {Alert, CartTotalPrice, CartProductList},
  created() {
    this.getCart();
  },
  computed: {
    ...mapState({
      isSentForm: state => state.cart.isSentForm,
      cart: state => state.cart.cart
    }),
    showCartContent() {
      console.log(this.cart.cartProducts);
      return !this.isSentForm && Object.keys(this.cart).length;
    }
  },
  methods: {
    ...mapActions({
      getCart: "cart/getCart",
      makeOrder: "cart/makeOrder"
    }),
  }
}
</script>

<style scoped>

</style>