<template>
  <table class="table table-cart table-mobile main-cart-show">
    <thead>
    <tr>
      <td>Product</td>
      <td>Price</td>
      <td>Quantity</td>
      <td>Total</td>
      <td></td>
    </tr>
    </thead>
    <tbody>
    <transition-group name="cartProducts-list">
    <cart-product-item
        v-for="cartProduct in cart.cartProducts"
        :key="cartProduct.id"
        :cartProduct="cartProduct"
    />
    </transition-group>
    </tbody>
  </table>
</template>

<script>
import CartProductItem from "./CartProductItem";
import {mapState} from "vuex";

export default {
  name: "CartProductList",
  components: {CartProductItem},
  computed: {
    ...mapState({
      cart: state => state.cart.cart,
    })
  },
}
</script>

<style scoped>
.cartProducts-list-item {
  display: inline-block;
  margin-right: 10px;
}

.cartProducts-list-move,
.cartProducts-list-enter-active,
.cartProducts-list-leave-active {
  transition: all 0.4s ease;
}

.cartProducts-list-enter-from,
.cartProducts-list-leave-to {
  opacity: 0;
  transform: translatex(-130px);
}
</style>