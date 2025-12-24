<script setup>
import InputError from "./InputError.vue";

const props = defineProps({
    options: Object,
    optionDefault: String,
    errors: String,
});

const model = defineModel();
</script>

<template>
    <div>
        <select
            v-model="model"
            class="w-full px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :class="[
                errors ? 'border border-red-500' : 'border border-gray-300',
            ]"
        >
            <option value="">{{ optionDefault }}</option>
            <option
                v-for="option in options"
                :value="option?.code || option?.id"
                :key="option?.code || options?.id"
            >
                {{ option?.name }}
            </option>
        </select>
        <InputError :message="errors" />
    </div>
</template>

<style scoped></style>
