<template>

</template>



<script setup>
  import { router, usePage } from '@inertiajs/vue3'
  import { ref, onMounted, computed, onUnmounted } from 'vue';
</script>
<style scoped>
  .notification-panel {
    max-height: 15rem;
    overflow-y: auto;
  }

  @media (max-width: 576px) {
    .notification-panel {
      max-height: 31vh;
    }
  }

  .notification-panel::-webkit-scrollbar {
    width: 8px;
  }

  .notification-panel::-webkit-scrollbar-thumb {
    background-color: #888;
    border-radius: 4px;
  }

  .notification-panel::-webkit-scrollbar-thumb:hover {
    background-color: #555;
  }

  .notification-panel::-webkit-scrollbar-track {
    background-color: #f1f1f1;
  }
</style>
