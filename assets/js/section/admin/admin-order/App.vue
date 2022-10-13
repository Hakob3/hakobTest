<template>
  <div class="table-additional-selection">
    <order-product-add/>
    <hr/>
    <transition-group name="orderProducts-list">
      <order-product-item
          v-for="(orderProduct, index) in orderProducts"
          :key="orderProduct.id"
          :orderProduct="orderProduct"
          :index="index"
      />
    </transition-group>
    <hr/>
    <total-price/>
  </div>
</template>

<script>
import {mapActions, mapState} from "vuex";
import OrderProductItem from "./components/OrderProductItem";
import OrderProductAdd from "./components/OrderProductAdd";
import TotalPrice from "./components/TotalPrice";

export default {
  components: {TotalPrice, OrderProductAdd, OrderProductItem},
  data() {
    return {
      order: 555
    }
  },
  created() {
    this.getCategories();
    this.getOrderProducts();
  },
  computed: {
    ...mapState({
      orderProducts: state => state.products.orderProducts
    }),
  },
  methods: {
    ...mapActions({
      getCategories: "products/getCategories",
      getOrderProducts: "products/getOrderProducts"
    })
  }
}
</script>

<style scoped>
.orderProducts-list-item {
  display: inline-block;
  margin-right: 10px;
}

.orderProducts-list-move,
.orderProducts-list-enter-active,
.orderProducts-list-leave-active {
  transition: all 0.4s ease;
}

.orderProducts-list-enter-from,
.orderProducts-list-leave-to {
  opacity: 0;
  transform: translatex(-130px);
}
</style>