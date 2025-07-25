<template>
    <div class="message-wrap">
        <ul>
            <li v-for="user in users" :key="user.id">
                <a href="">
                    <figure>
                        <img :src="user.profile_image_url" alt="message-profile">
                        <span v-if="isOnline(user.id)" class="online-status">Online</span>
                    </figure>
                    <div class="message-cont">
                        <div class="message-cont-head">
                            <h5>{{ user.name }}</h5>
                            <p class="time">{{ user.last_message_time }}</p>
                        </div>
                        <p>{{ user.last_message }}</p>
                        <p v-if="user.unread_messages_count > 0" class="counting">{{ user.unread_messages_count }}</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    conversations: Array,
});

const users = ref([]);
const onlineUsers = ref([]);

const isOnline = (userId) => {
    return onlineUsers.value.some(user => user.id === userId);
};

onMounted(() => {
    users.value = props.conversations.map(conversation => {
        const otherUser = conversation.participants.find(participant => participant.id !== window.Laravel.user.id);
        return {
            id: otherUser.id,
            name: otherUser.name,
            profile_image_url: otherUser.profile_photo_url,
            last_message: conversation.last_message?.body,
            last_message_time: conversation.last_message ? new Date(conversation.last_message.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '',
            unread_messages_count: conversation.unread_messages_count,
        };
    });

    window.Echo.join('online')
        .here((users) => {
            onlineUsers.value = users;
        })
        .joining((user) => {
            onlineUsers.value.push(user);
        })
        .leaving((user) => {
            onlineUsers.value = onlineUsers.value.filter(u => u.id !== user.id);
        });
});
</script>

<style scoped>
.online-status {
    position: absolute;
    bottom: 0;
    right: 0;
    background-color: #22c55e;
    color: white;
    border-radius: 50%;
    width: 15px;
    height: 15px;
    border: 2px solid white;
}
figure {
    position: relative;
}
</style>
