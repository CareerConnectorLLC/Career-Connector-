<script setup>
defineProps({
    users: {
        type: Array,
        required: true
    },
    role: {
        type: String,
        required: true
    }
})

const emit = defineEmits(['select-user'])

function selectUser(userId, serviceId) {
    emit('select-user', { userId, serviceId })
}
</script>

<template>
    <div class="messaging-left">
        <div class="message-card" style="height: 100%; padding-bottom: 0;">
            <div class="card-head">
                <div class="bookings-heading">
                    <h4 v-text="role === 'USER' ? 'Providers' : 'Customers'"></h4>
                </div>
                <div class="message-search d-none">
                    <form>
                        <div class="form-input">
                            <input type="text" placeholder="Search">
                        </div>
                    </form>
                </div>
            </div>

            <div class="message-wrap">
                <ul v-if="users.length">
                    <li v-for="(user, index) in users" :key="index">
                        <a href="" @click.prevent="selectUser(user.id, user.service_id)" class="d-flex align-items-center">
                            <figure v-if="user.profile_photo_path">
                                <img :src="user.profile_photo_url" :alt="user.name">
                            </figure>
                            <figure v-else>
                                <img src="/public/frontend_assets/images/profile-image-01.png" alt="message-profile">
                            </figure>
                            <div class="message-cont flex-grow-1">
                                <h5>{{ user.name }}</h5>
                            </div>
                            <p class="counting ms-auto" v-if="user.unread_messages_count > 0">{{ user.unread_messages_count }}</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped>
.message-cont h5 {
    margin-bottom: 0;
}

.counting {
    background-color: #174dba;
    color: #fff !important;
    border-radius: 50%;
    font-size: 12px;
    font-weight: bold;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>