<template>
  <div class="orderProducts row md-1">
    <div class="col-md-1 text-center">
      {{ rowNumber }}
    </div>
    <div class="col-md-2">
      {{ orderProduct.product.id }}: {{ productTitle }}
    </div>
    <div class="col-md-2">
      {{ categoryTitle }}
    </div>
    <div class="col-md-2">
      {{ orderProduct.quantity }}
    </div>
    <div class="col-md-2">
      ${{ orderProduct.pricePerOne }}
    </div>
    <div class="col-md-3">
      <button
          type="button"
          class="btn btn-outline-info"
          @click="viewDetails"
      >
        Details
      </button>
      <button
          type="button"
          class="btn btn-outline-danger"
          @click="remove"
      >
        Remove
      </button>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";

export default {
  name: "OrderProductItem",
  props: {
    orderProduct: {
      type: Object,
      default: () => {
      }
    },
    index: {
      type: Number,
      default: 0
    },
  },
  computed: {
    rowNumber() {
      return this.index + 1;
    },
    productTitle() {
      return this.orderProduct.product.title;
    },
    categoryTitle() {
      return this.orderProduct.product.category.title;
    },
    ...mapState({
      staticStore: state => state.products.staticStore
    }),
  },
  methods: {
    ...mapActions({
      removeOrderProduct: "products/removeOrderProduct"
    }),
    viewDetails() {
      const url = '/admin/product/edit/' + this.orderProduct.product.id;
      window.open(url, '_blank').focus();
    },
    remove() {
      this.removeOrderProduct(this.orderProduct.id);
    }
  }
}
</script>

<style scoped>

</style>