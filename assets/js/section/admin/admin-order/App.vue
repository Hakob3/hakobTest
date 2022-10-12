<template>
  <div class="table-additional-selection">
    <hr/>
      <order-product-item
          v-for="(orderProduct, index) in staticStore.orderProducts"
          :key="orderProduct.id"
          :orderProduct="orderProduct"
          :index="index"
      />
    <hr/>
  </div>
</template>

<script>
import {mapState} from "vuex";
import OrderProductItem from "./components/OrderProductItem";

export default {
  components: {OrderProductItem},
  data() {
    return {
      order: 555
    }
  },
  computed: {
    ...mapState({
      staticStore: state => state.products.staticStore
    }),
  },
}
</script>

<style>

</style>