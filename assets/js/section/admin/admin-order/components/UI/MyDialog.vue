<template>
<div v-if="show" class="dialog position-fixed d-flex" @click="hideDialog">
   <div class="m-auto bg-white rounded dialog__content p-5" @click.stop>
     <slot></slot>
   </div>
</div>
</template>

<script>
import toggleMixin from "../../mixins/toggleMixin";

export default {
  name: "my-dialog",
  mixins: [toggleMixin],
  mounted() {
    console.log('mounted dialog');
  }
}
</script>

<style scoped>
.dialog{
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: rgba(0, 0, 0, 0.5);
}

.dialog__content{
  min-height: 50px;
  min-width: 300px;
  background-color: rgb(255, 255, 255);
}
</style>