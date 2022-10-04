<template>
  <div class="app">
    <navbar></navbar>
    <router-view></router-view>
  </div>
</template>

<script>
import Navbar from "./components/UI/Navbar";
export default {
  components: {Navbar}
}
</script>

<style scoped>

</style>