import { ref, onMounted, onUnmounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

/**
 * A Vue composable to manage presence on a global channel.
 * It handles joining, leaving, and listening for other users.
 * It also announces the current user's presence to others upon joining.
 */
export function usePresenceChannel() {
    const page = usePage();
    const onlineUsers = ref([]);
    const currentUser = computed(() => page.props.auth.user);

    onMounted(() => {
        Echo.join('career-connector')
            .here((users) => {
                onlineUsers.value = users.filter(u => u.id !== currentUser.value.id);
                console.log(onlineUsers.value)
            }).joining((user) => {
                onlineUsers.value.push(user);
            }).leaving((user) => {
                onlineUsers.value = onlineUsers.value.filter(u => u.id !== user.id);
            }).error((error) => {
                console.error('Echo error:', error);
            });
    });

    onUnmounted(() => {
        Echo.leave('career-connector');
    })

    return { onlineUsers };
}