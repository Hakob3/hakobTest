<template>
  <div class="mt-3">
    <div v-if="products.length > 0">
      <h4>Список продуктов</h4>
      <transition-group name="products-list">
        <product-item
            v-for="product in products"
            :product="product"
            :key="product.id"
            @remove="$emit('remove', product)"
        />
      </transition-group>
    </div>
    <h2 v-else class="text-danger">
      Список продуктов пуст
    </h2>
  </div>
</template>

<script>
import ProductItem from "./ProductItem";
export default {
  components: {ProductItem},
  props: {
    products: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
  .products-list-item {
    display: inline-block;
    margin-right: 10px;
  }

  .products-list-move,
  .products-list-enter-active,
  .products-list-leave-active{
    transition: all 0.4s ease;
  }

  .products-list-enter-from,
  .products-list-leave-to {
    opacity: 0;
    transform: translatex(-130px);
  }
</style>