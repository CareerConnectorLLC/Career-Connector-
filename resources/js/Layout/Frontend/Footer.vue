<template>
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl">
          <div
            class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
            <div>
              ©{{new Date().getFullYear()}} Demo, a product of <a href="https://www.demo.com/" target="_blank" class="fw-semibold">Demo</a>
            </div>

          </div>
        </div>
      </footer>
</template>

<script setup>
    import { usePage } from '@inertiajs/vue3';
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
    </script>
