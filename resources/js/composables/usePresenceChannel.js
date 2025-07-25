import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

/**
 * A Vue composable to manage presence on a global channel.
 * It handles joining, leaving, and listening for other users.
 */

// --- Singleton State ---
const onlineUsers = ref([]);
let isInitialized = false;

export function usePresenceChannel() {
    // If already initialized, just return the shared state.
    if (isInitialized) {
        return { onlineUsers };
    }
    isInitialized = true;

    const page = usePage();
    const currentUser = computed(() => page.props.auth.user);

    // Watch for user login/logout to manage the channel connection.
    watch(currentUser, (newUser, oldUser) => {
        if (oldUser) {
            window.Echo.leave('career-connector');
        }
        if (newUser) {
            window.Echo.join('career-connector')
                .here((users) => {
                    // On joining, filter out the current user from the list.
                    onlineUsers.value = users.filter(u => u.id !== newUser.id);
                }).joining((user) => {
                    // When a new user joins, add them to the list.
                    onlineUsers.value.push(user);
                }).leaving((user) => {
                    // When a user leaves, remove them from the list.
                    onlineUsers.value = onlineUsers.value.filter(u => u.id !== user.id);
                }).error((error) => {
                    console.error('Echo presence channel error:', error);
                });
        }
    }, { immediate: true });

    // Return the shared reactive list of online users.
    return { onlineUsers };
}