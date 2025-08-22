<template>
    <Toast />
    <Toast position="top-right" group="message-toast">
        <template #message="slotProps">
            <div class="d-flex flex-column align-items-start" style="flex: 1; padding: 1rem;">
                <div class="d-flex align-items-center" style="gap: 0.5rem;">
                    <i class="pi pi-envelope" style="font-size: 1.5rem;"></i>
                    <span class="fw-bold text-dark">{{ slotProps.message.summary }}</span>
                </div>
                <p class="my-3 text-dark">{{ slotProps.message.detail }}</p>
                <Button class="p-button-sm" label="View Message" @click="navigateToConversation(slotProps.message.data.conversationId)"></Button>
            </div>
        </template>
    </Toast>

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
import Button from 'primevue/button';
import { computed, watch } from 'vue';
import { usePage, router } from "@inertiajs/vue3";
import Header from './Header.vue';
import Footer from './Footer.vue';
import { useGlobalMessageNotifier } from '../../composables/useGlobalMessageNotifier';
import { usePresenceChannel } from '../../composables/usePresenceChannel';
import { useToast } from 'primevue';

const page = usePage()
const user = computed(() => page.props.auth.user)
const toast = useToast();

watch(() => page.props.flash, (flash) => {
    if (flash && flash.success) {
        toast.add({ severity: 'success', summary: 'Success', detail: flash.success, life: 3000 });
    }
    if (flash && flash.error) {
        toast.add({ severity: 'error', summary: 'Error', detail: flash.error, life: 3000 });
    }
})

// This composable will handle listening for new messages and showing toasts.
useGlobalMessageNotifier();

// This composable handles the global presence channel connection.
usePresenceChannel();

// Function to handle clicks on the toast notification
function navigateToConversation(conversationId) {
    router.get(`/messaging?conversation=${conversationId}`);
}

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