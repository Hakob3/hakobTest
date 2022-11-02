<template>
  <div
      v-if="alert.message"
      :class="getAlertClass">
    {{ alert.message }}
  </div>
</template>

<script>
import {mapState} from "vuex";

export default {
  name: "Alert",
  computed: {
    ...mapState({
      alert: state => state.cart.alert
    }),
    getAlertClass() {
      return "alert alert-" + this.alert.type;
    }
  }
}
</script>

<style scoped>

</style>