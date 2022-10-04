<template>
  <form @submit.prevent>
    <h4>Add product</h4>
    <my-input
        v-focus
        v-model="product.title"
        type="text"
        placeholder="Name"/>
    <my-input
        v-model="product.price"
        type="text"
        placeholder="Price"/>
    <my-input
        v-model="product.quantity"
        type="text"
        placeholder="Quantity"/>
    <my-button
        @click="createProduct"
        class="btn-primary mt-3">
      Create
    </my-button>
  </form>
</template>
<script>
export default {
  data() {
    return {
      product: {
        id: '',
        title: '',
        price: '',
        quantity: '',
      }
    }
  },
  methods: {
    createProduct() {
      this.product.id = Date.now();
      this.$emit('create', this.product)
      this.product = {
        id: '',
        title: '',
        price: '',
        quantity: '',
      };
    },
  }
}
</script>

<style scoped>

</style>