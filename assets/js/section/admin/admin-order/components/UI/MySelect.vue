<template>
    <select v-model="modelValue" class="form-control" @change="changeOptions">
      <option disabled value="" class="mb-2 text-primary">Выберите из списка</option>
      <option
          v-for="option in options"
          :key="option.value"
          :value="option.value">
        {{ option.name }}
      </option>
    </select>
</template>

<script>
export default {
  name: "my-select",
  props: {
    modelValue: {
      type: String
    },
    options: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    changeOptions(event) {
      this.$emit('update:modelValue', event.target.value);
    }
  }
}
</script>

<style scoped>

</style>