import { computed, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import emitter from '@/eventBus';

// --- Singleton State ---
// This flag ensures the initialization logic runs only once per application lifecycle.
let isInitialized = false;

export function useGlobalMessageNotifier() {
    // If the notifier has already been set up, do nothing.
    if (isInitialized) {
        return;
    }
    isInitialized = true;

    const page = usePage();
    const user = computed(() => page.props.auth.user);
    const toast = useToast();

    const handleIncomingNotification = (event) => {
        // Don't show a toast for our own sent messages.
        if (user.value && event.message.sender_id === user.value.id) {
            return;
        }

        // Emit a global event that other components (like Messaging.vue) can listen to.
        emitter.emit('global-message-received', event);

        // Don't show a toast if the user is already on the messaging page.
        // The Messaging.vue component will handle the incoming message.
        if (page.url.startsWith('/messaging')) {
            return;
        }

        const message = event.message;

        // Show a toast notification using a specific group we will define in the layout.
        toast.add({
            group: 'message-toast',
            summary: `New message from ${message.sender.name}`,
            detail: message.body.substring(0, 70) + (message.body.length > 70 ? '...' : ''),
            life: 8000,
            data: {
                conversationId: message.conversation_id
            }
        });
    };

    watch(user, (currentUser, previousUser) => {
        // Clean up the old channel listener if the user changes (e.g., logs out).
        if (previousUser) {
            window.Echo.leave(`private.user.${previousUser.id}`);
        }

        // Set up the new channel listener if we have a new user (e.g., logs in).
        if (currentUser) {
            window.Echo.private(`private.user.${currentUser.id}`)
                .listen('MessageSent', handleIncomingNotification);
        }
    }, { immediate: true });
}