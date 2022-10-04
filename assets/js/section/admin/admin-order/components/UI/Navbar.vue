<template>
  <div>
    <my-button
        @click="$router.push('/vue/products')"
    >
      Products
    </my-button>
    <my-button
        @click="$router.push('/vue/main')"
    >
      Main
    </my-button>
    <my-button
        @click="$router.push('/vue/store')"
    >
      Store
    </my-button>
    <my-button
        @click="$router.push('/vue/composition')"
    >
      Composition
    </my-button>
  </div>
</template>

<script>
import MyButton from "./MyButton";

export default {
  name: "Navbar",
  components: {MyButton}
}
</script>

<style scoped>

</style>