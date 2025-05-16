<script setup>
defineProps({
    showPass: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <button type="button" class="close" @click="$emit('handle-password-toggle')">
        <template v-if="!showPass">
            <img class="close-eye" src="/public/frontend_assets/images/close-eye.svg" alt="close-eye">
        </template>

        <template v-if="showPass">
            <img class="open-eye" src="/public/frontend_assets/images/eye.svg" alt="opne-eye">
        </template>
    </button>
</template>