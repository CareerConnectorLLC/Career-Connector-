<script setup>
import { ref } from "vue";
import { router, Link } from '@inertiajs/vue3';

defineProps({
    user: {
        type: Object,
        required: true
    }
})

const showDropdown = ref(false)
</script>

<template>
    <div class="dashboard-head-profile" v-bind:class="{ 'active': showDropdown }" @click="showDropdown = !showDropdown">
        <div class="dashboard-profile-wrap">
            <div class="dashboard-profile-image">
                <figure v-if="user.profile_photo_url"><img :src="user.profile_photo_url" alt="devid"></figure>
                <figure v-else><img src="/public/frontend_assets/images/devid.png" alt="devid"></figure>
            </div>
            <div class="dashboard-profile-cont">
                <span>{{ user.name }}</span>
            </div>
        </div>
        <div class="dashboard-profile-dropdown">
            <ul>
                <li v-bind:class="{ 'active': $page.url === `/provider-profile` }">
                    <Link :href="`/provider-profile`">My Profile</Link>
                </li>
                <li v-bind:class="{ 'active': $page.url === `/provider-dashboard` }">
                    <Link :href="`/provider-dashboard`">My Dashboard</Link>
                </li>
                <li>
                    <a href="" @click.prevent="router.post(`/logout`)">Log Out</a>
                </li>
            </ul>
        </div>
    </div>
</template>