<template>
    <Toast />
    <template v-if="isAuthPage">
        <Header :user="user" />
    </template>

    <slot />
    
    <template v-if="isAuthPage">
        <Footer />
    </template>
</template>

<script setup>
import Toast from 'primevue/toast';
import { computed } from 'vue';
import { usePage } from "@inertiajs/vue3";
import Header from './Header.vue';
import Footer from './Footer.vue';

const page = usePage()
const user = computed(() => page.props.auth.user)

const isAuthPage = computed(() => {
    let paths = [
        '/',
        '/blog',
        '/blog/*',
        '/blog?*',
        '/contact-us',
        '/provider-listing?*',
        '/provider/*',
    ]
    return matchesPath(page.url, paths)
})

function matchesPath(pageUrl, paths) {
    for (let path of paths) {
        if (path.includes('?*')) {
            // Case where path expects optional query params
            const basePath = path.replace('?*', '');
            if (pageUrl === basePath || pageUrl.startsWith(basePath + '?')) {
                return true;
            }
        } else if (path.includes('*')) {
            // Case where path includes wildcard for sub-paths
            const basePath = path.replace('*', '');
            if (pageUrl.startsWith(basePath)) {
                return true;
            }
        } else {
            // Exact path match
            if (pageUrl === path) {
                return true;
            }
        }
    }
    return false;
}
</script>

<style>
    @import "bootstrap/dist/css/bootstrap.min.css";
    @import url('https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css');
    @import url('https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.4/css/splide.min.css');
    @import "../../../../public/frontend_assets/style.css";
</style>