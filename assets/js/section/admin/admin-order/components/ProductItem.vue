<template>
  <div class="row d-flex justify-content-between align-items-center">
    <div class="col-7">
      <div class="product rounded border-success border-3 p-3">
        <div><strong>id: </strong>{{ product.id }}</div>
        <div><strong>Name: </strong>{{ product.title }}</div>
        <div><strong>Price: </strong>{{ product.price }}</div>
        <div><strong>Quantity: </strong>{{ product.quantity }}</div>
      </div>
    </div>
    <div class="col-3 text-right">
      <my-button
          @click="$router.push(`/vue/products/${product.id}`)"
          class="btn-primary">
        Открыть
      </my-button>
      <my-button
          @click="$emit('remove', product)"
          class="btn-danger">
        Удалить
      </my-button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    product: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>

</style>