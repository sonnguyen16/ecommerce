<script setup lang="ts">
import { CheckBadgeIcon } from '@heroicons/vue/24/solid'
import { XCircleIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  message: {
    type: String as PropType<string>,
    required: true
  },
  type: {
    type: String as PropType<'success' | 'error'>,
    required: true
  },
  show: {
    type: Boolean,
    default: false
  }
})

const refShow = ref(props.show)
const emits = defineEmits(['update:show'])

watch(
  () => props.show,
  (value) => {
    refShow.value = value
    if (value) {
      setTimeout(() => {
        refShow.value = false
        emits('update:show', false)
      }, 3000)
    }
  }
)
</script>

<template>
  <div
    id="toast-top-right"
    :class="refShow ? 'right-5' : '-right-[300px]'"
    class="bottom-5 bg-white z-10 transition-all fixed duration-500 flex items-center w-full max-w-xs p-4 space-x-4 text-gray-500 divide-x rtl:divide-x-reverse divide-gray-200 rounded-lg border border-1 dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800"
    role="alert"
  >
    <CheckBadgeIcon v-if="type === 'success'" class="w-8 h-8 text-green-500" />
    <XCircleIcon v-else class="w-8 h-8 text-red-500" />
    <div class="text-lg font-normal ps-3">{{ message }}</div>
  </div>
</template>

<style scoped></style>
