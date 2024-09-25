<script setup>
const props = defineProps({
  errors: String,
  placeholder: {
    type: String,
    default: "",
  },
  rows: {
    type: Number,
    default: 5,
  },
})

const model = defineModel();
</script>

<template>
  <div>
    <textarea v-model="model"
           :placeholder="props.placeholder"  :rows="props.rows"
           :class="[errors ? 'border border-red-500' : 'border border-gray-300']"
           class="w-full px-4 py-2  rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
    </textarea>
    <InputError :message="errors" />
  </div>
</template>
