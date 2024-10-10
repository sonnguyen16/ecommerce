<script setup>
const props = defineProps({
  type: String,
  errors: String,
  disabled: {
    type: Boolean,
    default: false,
  },
  placeholder: {
    type: String,
    default: "",
  },
})

const model = defineModel();
</script>

<template>
  <div>
    <input v-model="model" :type="props.type" :disabled="disabled"
           :placeholder="props.placeholder"
           :class="[errors ? 'border border-red-500' : 'border border-gray-300']"
           class="w-full px-4 py-2  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800"
    />
    <InputError :message="errors" />
  </div>
</template>
