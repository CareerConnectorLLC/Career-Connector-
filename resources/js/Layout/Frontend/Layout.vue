<template>
    <Toast />
    <Header/>
    <Sidebar/>

    <slot/>
    <Footer/>

</template>

<script setup>
    import Toast from 'primevue/toast';
    import { onMounted, onBeforeUnmount, ref } from 'vue';
    import { usePage, router } from "@inertiajs/vue3";
    import Header from './Header.vue';
    import Sidebar from './Sidebar.vue';
    import Footer from './Footer.vue';



</script>
<style scoped>

</style>
