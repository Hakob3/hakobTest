<template>
  <div class="row md-2">
    <div class="col-md-2">
      <select
          v-model="form.categoryId"
          name="add-product_category"
          class="form-control"
          @change="getProducts"
      >
        <option value="" disabled> Choose category</option>
        <option
            v-for="category in categories"
            :key="category.id"
            :value="category.id"
        >
          {{ category.title }}
        </option>
      </select>
    </div>
    <div
        v-if="form.categoryId"
        class="col-md-3"
    >
      <select
          v-model="form.product"
          name="add-product_product"
          class="form-control"
      >
        <option value="" disabled>Choose product</option>
        <option
            v-for="categoryProduct in freeCategoryProducts"
            :key="categoryProduct.uuid"
            :value="categoryProduct.uuid"
        >
          {{ categoryProduct.id }}: {{ categoryProduct.title }}
        </option>
      </select>
    </div>
    <div class="col-md-2" v-if="form.product">
      <input
          v-model="form.quantity"
          type="number"
          class="form-control"
          placeholder="Quantity"
          min="1"
          :max="productsQuantityMax"
      >
    </div>
    <div class="col-md-2" v-if="form.product">
      <input
          v-model="form.pricePerOne"
          type="number"
          class="form-control"
          placeholder="Price per one"
          min="1"
          step="any"
          :max="productsPriceMax"
      >
    </div>
    <div class="col-md-3">
      <button
          v-if="form.product"
          type="button"
          class="btn btn-outline-info"
          @click="viewDetails"
      >
        Details
      </button>
      <button
          v-if="form.product"
          type="button"
          class="btn btn-outline-success"
          @click="addOrderProduct"
      >
        Add
      </button>
    </div>
  </div>
</template>

<script>
import {mapState, mapMutations, mapActions, mapGetters} from "vuex";

export default {
  name: "order-product-add",
  data() {
    return {
      form: {
        categoryId: "",
        product: "",
        quantity: "",
        pricePerOne: "",
      }
    };
  },
  computed: {
    ...mapState({
      categories: state => state.products.categories,
      categoryProducts: state => state.products.categoryProducts
    }),
    ...mapGetters({
      freeCategoryProducts: "products/freeCategoryProducts"
    }),
    productsQuantityMax() {
      const productData = this.freeCategoryProducts.find(
          product =>product.uuid === this.form.product
      );
      return parseInt(productData.quantity);
    },
    productsPriceMax() {
      const productData = this.freeCategoryProducts.find(
          product =>product.uuid === this.form.product
      );
      return parseFloat(productData.price);
    }
  },
  methods: {
    ...mapMutations({
      setNewProductData: "products/setNewProductData"
    }),
    ...mapActions({
      getProductsByCategory: "products/getProductsByCategory",
      addNewOrderProduct: "products/addNewOrderProduct"
    }),
    getProducts() {
      this.setNewProductData(this.form);
      this.getProductsByCategory();
    },
    viewDetails() {
      const url = '/admin/product/edit/' + this.form.product;
      window.open(url, '_blank').focus();
    },
    addOrderProduct() {
      this.setNewProductData(this.form);
      this.addNewOrderProduct();
      this.resetFormData();
    },
    resetFormData() {
      Object.assign(this.$data, this.$options.data.apply(this))
    }
  }
}
</script>

<style scoped>

</style>