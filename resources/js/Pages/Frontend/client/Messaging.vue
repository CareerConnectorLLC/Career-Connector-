<script setup>
import { computed, ref, onMounted, onUnmounted, reactive, watch, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import emitter from '@/eventBus';

import ProfileDropdown from "../../../components/frontend/customer/ProfileDropdown.vue";
import CustomerSidebar from "../../../components/frontend/customer/LeftSidebar.vue";
import ProviderSidebar from '../../../components/frontend/provider/SideNavigation.vue';
import UserListing from "../../../components/frontend/messaging/UserListing.vue";
import { usePresenceChannel } from "../../../composables/usePresenceChannel.js";

const { onlineUsers } = usePresenceChannel();
const page = usePage()
const user = computed(() => page.props.auth.user)
const conversations = ref(page.props.conversations)
const activeConversation = ref(null)
const messages = ref([])
const isLoadingMessages = ref(false)
const otherUserIsTyping = ref(false);
const chatDisplayRef = ref(null);
const validationErrors = ref({});
const typingUserName = ref('');

let typingTimeout = null;
let typingWhisperTimer = null;

const handleGlobalMessage = (e) => {
    // This will catch messages for ALL conversations for this user, via the event bus.

    // Check if the message is for the currently active conversation.
    if (activeConversation.value && e.message.conversation_id === activeConversation.value.id) {
        // If it is, the other listener (`private.conversation.[id]`) will handle it.
        // We do nothing here to avoid double processing.
        return;
    }

    // If it's for an INACTIVE conversation, find it and update the unread count.
    const conversationInList = conversations.value.find(c => c.id === e.message.conversation_id);
    if (conversationInList) {
        // Increment the count. Vue's reactivity will update the UI.
        conversationInList.unread_messages_count = (conversationInList.unread_messages_count || 0) + 1;
    }
};

const chatMsg = reactive({
    body: '',
    sender_id: user.value.id,
})

onMounted(() => {
    console.log(user.value.role);
    
    let initialConversation = null;
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('conversation')) {
        // The conversation ID from URL can be a string, ensure it's a number for comparison
        const conversationId = parseInt(urlParams.get('conversation'), 10);
        initialConversation = conversations.value.find(c => c.id === conversationId);
    }
    
    // If no valid conversation from URL, default to the first in the list
    if (!initialConversation && conversations.value.length > 0) {
        initialConversation = conversations.value[0];
    }

    if (initialConversation) {
        activeConversation.value = initialConversation;
        chatMsg.conversation_id = initialConversation.id;
        fetchChatMessages(initialConversation.id);
    }

    // Listen for global message events from the event bus.
    emitter.on('global-message-received', handleGlobalMessage);
})

watch(
    () => activeConversation.value,
    (newConversation, oldConversation) => {
        if (oldConversation) {
            Echo.leave(`private.conversation.${oldConversation.id}`);
        }

        // Reset typing indicator when switching conversations
        otherUserIsTyping.value = false;
        clearTimeout(typingTimeout);

        if (newConversation) {
            Echo.private(`private.conversation.${newConversation.id}`)
                .listenForWhisper('typing', (e) => {
                    otherUserIsTyping.value = true;
                    typingUserName.value = e.name;

                    if (typingTimeout) {
                        clearTimeout(typingTimeout);
                    }
                    // Hide indicator after 3 seconds of inactivity
                    typingTimeout = setTimeout(() => {
                        otherUserIsTyping.value = false;
                    }, 3000);
                })
                .listen('MessageSent', (e) => {
                    // The broadcast event contains the message object.
                    // We only want to push it if it's for the currently active conversation.
                    if (e.message.conversation_id === newConversation.id) { 
                        // When a message is received, the user is no longer typing.
                        otherUserIsTyping.value = false;
                        clearTimeout(typingTimeout);

                        // Do not push the message if the current user is the sender.
                        // The sender's message is already added optimistically.
                        if (e.message.sender_id === user.value.id) {
                            return;
                        }
                        handleIncomingMessage(e.message);
                    }
                });
        }
    }
)

watch(() => chatMsg.body, (newValue) => {
    // If there's a validation error, check if the user's edit has resolved it.
    if (validationErrors.value.body && newValue.length <= 1000) {
        validationErrors.value = {};
    }

    if (!activeConversation.value || typingWhisperTimer) {
        return;
    }

    Echo.private(`private.conversation.${activeConversation.value.id}`)
        .whisper('typing', {
            name: user.value.name
        });

    // Throttle whisper events to once every 1.5 seconds
    typingWhisperTimer = setTimeout(() => {
        typingWhisperTimer = null;
    }, 1500);
});

onUnmounted(() => {
    // Leave the active conversation channel if it exists
    if (activeConversation.value) {
        Echo.leave(`private.conversation.${activeConversation.value.id}`);
    }

    // Clean up the event bus listener.
    emitter.off('global-message-received', handleGlobalMessage);
});

function getConversationUsers() {
    return conversations.value.map(conversation => {
        if (user.value.role === 'USER') {
            conversation.provider['service_id'] = conversation.service.id;
            conversation.provider['unread_messages_count'] = conversation.unread_messages_count;
            return conversation.provider
        } else {
            conversation.customer['service_id'] = conversation.service.id;
            conversation.customer['unread_messages_count'] = conversation.unread_messages_count;
            return conversation.customer
        }
    })
}

function initiateConversation(param) {
    let conversation = conversations.value.find(conversation => {
        if (user.value.role === 'USER') {
            return conversation.provider.id == param.userId && conversation.service.id == param.serviceId
        } else {
            return conversation.customer.id == param.userId && conversation.service.id == param.serviceId
        }
    })

    if (conversation) {
        activeConversation.value = conversation
        chatMsg.conversation_id = conversation.id
        fetchChatMessages(conversation.id)
    }
}

async function fetchChatMessages(id) {
    messages.value = []
    isLoadingMessages.value = true
    try {
        const response = await axios.get(`/messaging/${id}`)
        messages.value = response.data.messages
    } catch (error) {
        console.error('Failed to fetch messages:', error)
    } finally {
        isLoadingMessages.value = false
        await scrollToBottom();

        // After loading and scrolling, check if the conversation should be marked as read.
        // This is crucial for conversations that load without a scrollbar, as no
        // scroll event would be fired to trigger the handleScroll() function.
        const el = chatDisplayRef.value;
        if (el && activeConversation.value && activeConversation.value.unread_messages_count > 0) {
            if (el.scrollHeight <= el.clientHeight) {
                markConversationAsRead();
            }
        }
    }
}

function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    const day = date.toLocaleDateString('en-US', { weekday: 'long' });
    const time = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    return `${day}, <span>${time}</span>`;
}

async function sendMsg() {
    if (!activeConversation.value) {
        return;
    }

    // Create a temporary message for optimistic UI update.
    const tempId = Date.now();
    const originalMessageBody = chatMsg.body;
    const optimisticMessage = {
        id: tempId, // Use a temporary unique ID for the key
        body: originalMessageBody,
        sender_id: user.value.id,
        created_at: new Date().toISOString(),
        sender: { ...user.value }, // Create a shallow copy for the sender info
    };

    // Update the UI first.
    messages.value.push(optimisticMessage);
    await scrollToBottom();
    // Clear the input field.
    chatMsg.body = '';

    try {
        // Now, make the API call.
        validationErrors.value = {}; // Clear previous errors
        const payload = {
            body: originalMessageBody,
            conversation_id: activeConversation.value.id,
        };
        const response = await axios.post('/chat-message', payload);

        // On success, find the optimistic message and replace it with the real one from the server.
        const savedMessage = response.data.message;
        const messageIndex = messages.value.findIndex(m => m.id === tempId);
        if (messageIndex !== -1) {
            messages.value[messageIndex] = savedMessage;
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            // Assign validation errors from the server response
            validationErrors.value = error.response.data.errors;
        } else {
            console.error('Failed to send message:', error);
        }

        // For any error, restore the UI to its previous state
        const messageIndex = messages.value.findIndex(m => m.id === tempId);
        if (messageIndex !== -1) {
            messages.value.splice(messageIndex, 1);
        }
        chatMsg.body = originalMessageBody;
    }
}

function handleScroll() {
    const el = chatDisplayRef.value;
    if (!el || !activeConversation.value || activeConversation.value.unread_messages_count === 0) {
        return;
    }

    // Check if scrolled to the bottom (with a 10px tolerance)
    if (el.scrollTop + el.clientHeight >= el.scrollHeight - 10) {
        markConversationAsRead();
    }
}

function scrollToBottom() {
    return new Promise(resolve => {
        // nextTick waits for Vue to update the DOM with the new messages.
        nextTick(() => {
            // However, even after the DOM update, the browser might not have finished
            // its layout calculations to determine the final scrollHeight.
            // A setTimeout with a 0ms delay pushes the scroll logic to the end of
            // the browser's event loop, ensuring the layout is complete.
            setTimeout(() => {
                const el = chatDisplayRef.value;
                if (el) {
                    el.scrollTop = el.scrollHeight;
                }
                resolve();
            }, 0);
        });
    });
}

async function handleIncomingMessage(message) {
    const el = chatDisplayRef.value;
    // Check if user is near the bottom BEFORE adding the new message. A 20px tolerance is good.
    const isAtBottom = el ? (el.scrollTop + el.clientHeight >= el.scrollHeight - 20) : true;

    messages.value.push(message);

    if (isAtBottom) {
        // If the user was at the bottom, scroll to keep them there and mark the message as read.
        await scrollToBottom();
        markConversationAsRead();
    } else {
        // If the user is scrolled up, increment the unread count for the active conversation.
        const conversationInList = conversations.value.find(c => c.id === activeConversation.value.id);
        if (conversationInList) {
            conversationInList.unread_messages_count++;
        }
    }
}

async function markConversationAsRead() {
    if (!activeConversation.value) return;

    const conversationId = activeConversation.value.id;
    // Find the active conversation in the conversations list
    const conversation = conversations.value.find(c => c.id === conversationId);

    // If found, set its unread count to 0. This will update the UI.
    if (conversation) {
        conversation.unread_messages_count = 0;
    }

    try {
        await axios.post(`/conversations/${conversationId}/read`);
    } catch (error) {
        console.error(`Failed to mark conversation ${conversationId} as read:`, error);
        // Optional: You could add logic here to revert the unread count if the API call fails.
    }
}
</script>

<template>
    <div class="dashboard-sec message">
        <div class="dashboard-container">
            <div class="dashboard-head">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>

                <h1>Messaging</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="nofication">
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/notification.svg" alt="nofication">
                                    <span class="notification-indecator"></span>
                                </figure>
                            </a>
                        </div>

                        <!-- Profile Dropdown -->
                        <ProfileDropdown :user="user" />
                    </div>
                </div>
            </div>
            <div class="dashboard-inner-wrap">
                <!-- Left sidebar panel -->
                <CustomerSidebar v-if="user.role === 'USER'" />
                <ProviderSidebar v-if="user.role === 'SERVICE-PROVIDER'" />

                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="messaging-wrap">
                            <!-- User Listing Component -->
                            <UserListing :conversations="conversations" :role="user.role" @select-user="initiateConversation" />
                            
                            <!-- User Messaging Area -->
                            <div class="messaging-center" v-if="activeConversation">
                                <div class="chat-card">
                                    <div class="chat-head">
                                        <button class="message-toggler">
                                            <img src="/public/frontend_assets/images/arrow-left.svg" alt="left-arrow">
                                        </button>
                                        <div class="chat-profile" v-if="user.role === 'USER'">
                                            <figure v-if="activeConversation.provider.profile_photo_path">
                                                <img :src="activeConversation.provider.profile_photo_url" :alt="activeConversation.provider.name">
                                            </figure>
                                            <figure v-if="!activeConversation.provider.profile_photo_path">
                                                <img src="/public/frontend_assets/images/profile-image-01.png" alt="/message-profile">
                                            </figure>
                                            <div class="chat-profile-details">
                                                <h3>{{ activeConversation.provider.name }}</h3>
                                                <p class="status" v-show="onlineUsers.some(user => user.id === activeConversation.provider.id)">Online</p>
                                            </div>
                                        </div>
                                        <div class="chat-profile" v-if="user.role === 'SERVICE-PROVIDER'">
                                            <figure v-if="activeConversation.customer.profile_photo_path">
                                                <img :src="activeConversation.customer.profile_photo_url" :alt="activeConversation.customer.name">
                                            </figure>
                                            <figure v-if="!activeConversation.customer.profile_photo_path">
                                                <img src="/public/frontend_assets/images/profile-image-01.png" alt="/message-profile">
                                            </figure>
                                            <div class="chat-profile-details">
                                                <h3>{{ activeConversation.customer.name }}</h3>
                                                <p class="status" v-show="onlineUsers.some(user => user.id === activeConversation.customer.id)">Online</p>
                                            </div>
                                        </div>
                                        <!-- chat head option -->
                                        <div class="chat-head-option">
                                            <span class="badge bg-warning">{{ activeConversation.service.name }}</span>
                                        </div>
                                    </div>
                                    <div class="chat-display">
                                        <div class="chat-message-outer" ref="chatDisplayRef" @scroll="handleScroll">
                                            <div class="chat-message-wrap" v-if="isLoadingMessages">
                                                <p class="text-center p-4">Loading messages...</p>
                                            </div>
                                            <div class="chat-message-wrap" v-else-if="messages.length > 0">
                                                <div v-for="message in messages" :key="message.id" class="chat-item" :class="message.sender_id === user.id ? 'customer' : 'provider'">
                                                    <div class="chat-item-wrap">
                                                        <div class="message-card-wrap">
                                                            <div class="message-card-wrap-item">
                                                                <div class="chat-message-card">
                                                                    <!-- Assuming message content is in 'body' attribute -->
                                                                    <p v-html="message.body"></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <figure>
                                                            <img v-if="message.sender.profile_photo_path" :src="message.sender.profile_photo_url" :alt="message.sender.name">
                                                            <img v-else src="/public/frontend_assets/images/profile-image-01.png" alt="profile-image">
                                                        </figure>
                                                    </div>
                                                    <p v-html="formatTimestamp(message.created_at)"></p>
                                                </div>
                                            </div>
                                            <div class="chat-message-wrap" v-else>
                                                <p class="text-center p-4">No messages yet. Start the conversation!</p>
                                            </div>
                                        </div>
                                        <div class="typing-indicator-wrap py-3" v-if="otherUserIsTyping">
                                            <p class="typing-indicator">{{ typingUserName }} is typing...</p>
                                        </div>
                                        <div class="chat-typing-section">
                                            <form @submit.prevent="sendMsg">
                                                <div class="chat-textarea">
                                                    <textarea v-model="chatMsg.body" placeholder="Type here something..." @keydown.enter.prevent="sendMsg"></textarea>
                                                    <input type="submit" value="submit">
                                                </div>
                                                <span v-if="validationErrors.body" class="text-danger">{{ validationErrors.body[0] }}</span>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="messaging-center" v-else>
                                <div class="chat-card">
                                    <div class="chat-display d-flex align-items-center justify-content-center h-100">
                                        <div class="text-center text-muted">
                                            <h4 v-if="conversations.length > 0">Select a conversation to start messaging.</h4>
                                            <h4 v-else>You have no conversations yet.</h4>
                                            <p v-if="conversations.length === 0">When a new conversation starts, it will appear here.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Information Area -->
                            <div class="messaging-right d-none">
                                <div class="message-share-file">
                                    <div class="link-lists">
                                        <div class="provider-details-head"></div>
                                    </div>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </div>
                <div class="sidebar-overlay"></div>
            </div>
        </div>


        <div class="top-left-shape">
            <img src="/public/frontend_assets/images/top-left-image.png" alt="">
        </div>
        <div class="top-right-shape">
            <img src="/public/frontend_assets/images/top-right-image.png" alt="">
        </div>
        <div class="bottom-left-shape">
            <img src="/public/frontend_assets/images/bottom-left-shap.png" alt="">
        </div>
        <div class="bottom-right-shape">
            <img src="/public/frontend_assets/images/bottom-image.png" alt="">
        </div>
        <div class="top-center">
            <img src="/public/frontend_assets/images/center-image.png" alt="">
        </div>
    </div>
</template>

<style scoped>
.messaging-center{ 
    width: 75.5%;
}

.typing-indicator-wrap {
    height: 24px;
    padding: 0 20px;
    display: flex;
    align-items: center;
}

.typing-indicator {
    font-style: italic;
    color: #888;
    font-size: 14px;
}
</style>