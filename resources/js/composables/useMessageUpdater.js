import { onMounted, onUnmounted } from 'vue';
import emitter from '@/eventBus';

/**
 * A composable that listens for global message events and updates a
 * reactive conversations array in real-time.
 *
 * @param {import('vue').Ref<Array>} conversations The reactive ref containing the list of conversations.
 */
export function useMessageUpdater(conversations) {

    const handleGlobalMessage = (e) => {
        // For debugging: to ensure the event is being caught.
        // console.log('Dashboard received global message:', e);

        if (!conversations.value) {
            return;
        }
        const conversationIndex = conversations.value.findIndex(c => c.id === e.message.conversation_id);

        if (conversationIndex !== -1) {
            // Create a new array to ensure reactivity is triggered.
            const newConversations = [...conversations.value];

            // Create a new object for the conversation that needs updating.
            const updatedConversation = { ...newConversations[conversationIndex] };

            updatedConversation.unread_messages_count = (updatedConversation.unread_messages_count || 0) + 1;

            // Ensure last_message is an object before updating
            const lastMessage = updatedConversation.last_message ? { ...updatedConversation.last_message } : {};
            lastMessage.body = e.message.body;
            lastMessage.created_at = e.message.created_at;
            updatedConversation.last_message = lastMessage;

            newConversations[conversationIndex] = updatedConversation;
            conversations.value = newConversations;
        }
    };

    onMounted(() => emitter.on('global-message-received', handleGlobalMessage));
    onUnmounted(() => emitter.off('global-message-received', handleGlobalMessage));
}