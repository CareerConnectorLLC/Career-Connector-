import { onMounted, onUnmounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import emitter from '@/eventBus';

export function useGlobalMessageNotifier() {
    const page = usePage();
    const user = computed(() => page.props.auth.user);
    const toast = useToast();

    const handleIncomingNotification = (event) => {
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

    onMounted(() => {
        if (user.value) {
            // Listen on the private channel for the authenticated user.
            window.Echo.private(`private.user.${user.value.id}`)
                .listen('MessageSent', handleIncomingNotification);
        }
    });

    onUnmounted(() => {
        // Clean up the listener when the user logs out or the app closes.
        if (user.value) {
            window.Echo.leave(`private.user.${user.value.id}`);
        }
    });
}