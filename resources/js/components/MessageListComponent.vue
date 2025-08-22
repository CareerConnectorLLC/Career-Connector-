<template>
    <div class="message-wrap">
        <ul>
            <li v-for="message in processedConversations" :key="message.id">
                <Link :href="`/messaging?conversation=${message.id}`" class="d-flex align-items-center">
                    <figure>
                        <img :src="message.avatar" alt="message-profile">
                    </figure>
                    <div class="message-cont">
                        <div class="message-cont-head">
                            <h5>{{ message.name }}</h5>
                            <span v-if="message.lastMessageTime" class="time">{{ formatTime(message.lastMessageTime) }}</span>
                        </div>
                        <div class="message-cont-bottom">
                            <p class="last-message" v-if="message.lastMessage">{{ message.lastMessage }}</p>
                            <p v-if="message.unreadCount > 0" class="counting">{{ message.unreadCount }}</p>
                        </div>
                    </div>
                </Link>
            </li>
            <li v-if="!processedConversations || processedConversations.length === 0">
                <p style="padding: 1rem; text-align: center; color: #6c757d;">No messages yet.</p>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    conversations: {
        type: Array,
        required: true
    },
    role: {
        type: String,
        required: true
    }
});

const processedConversations = computed(() => {
    if (!props.conversations) {
        return [];
    }
    return props.conversations.map(conversation => {
        const isClient = props.role === 'USER';
        const participant = isClient ? conversation.provider : conversation.customer;

        return {
            id: conversation.id,
            participantId: participant.id,
            name: participant.name,
            avatar: participant.profile_photo_url,
            lastMessage: conversation.last_message ? conversation.last_message.body : '',
            lastMessageTime: conversation.last_message ? conversation.last_message.created_at : '',
            unreadCount: conversation.unread_messages_count
        }
    })
});

const formatTime = (timestamp) => {
    const date = new Date(timestamp);
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
};
</script>

<style scoped>
.message-cont-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.message-cont-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2px;
}

.time {
    font-size: 0.8em;
    color: #999;
}

.last-message {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px; /* Adjust as needed */
    color: #6c757d;
    margin-bottom: 0;
}

.counting {
    margin-bottom: 0;
}
</style>