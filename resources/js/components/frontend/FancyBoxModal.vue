<script setup>
import { ref } from "vue";

defineProps({
    heading: { type: String },
    caption: { type: String },
    id: { type: String, default: null }
})
</script>

<template>
    <div class="change-passpord" :id="id ?? `change-passpord`" style="display: none;">
        <h2>{{ heading }}</h2>
        <p>{{ caption }}</p>
        <slot />
    </div>
</template>