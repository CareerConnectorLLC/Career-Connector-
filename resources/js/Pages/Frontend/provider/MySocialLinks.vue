<script setup>
import { onMounted, ref, onUnmounted } from "vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

import ProviderSidebar from '../../../components/frontend/provider/SideNavigation.vue';
import ProfileDropdown from '../../../components/frontend/provider/ProfileDropdown.vue';

import { useGlobalMessageNotifier } from '../../../composables/useGlobalMessageNotifier';
import { usePresenceChannel } from '../../../composables/usePresenceChannel';

// Initialize real-time listeners for notifications and presence.
useGlobalMessageNotifier();
usePresenceChannel();

const page = usePage()
const isOpen = ref(false)

const form = useForm({
    links: page.props.social_links.reduce((acc, link) => {
        if (link.name) {
            acc[link.name] = link.url;
        }
        return acc;
    }, {})
})

const user = page.props.auth.user

const socialLinks = ['Facebook', 'Instagram', 'LinkedIn', 'X', 'Youtube']

function toggleDashboard() {
    isOpen.value = !isOpen.value;
    document.body.classList.toggle('open-sidebar', isOpen.value);
    document.documentElement.classList.toggle('open-sidebar', isOpen.value);
}

function submit() {
    form.transform((data) => ({
        links: Object.entries(data.links)
            // Filter out links that are empty or just whitespace
            .filter(([, url]) => url && url.trim() !== '')
            // Create the array of objects for the backend
            .map(([name, url]) => ({ name, url: url.trim() })),
    }))
    .post('/social-links');
}
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>

                <h1>Social Links</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="nofication d-none">
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/notification.svg" alt="nofication">
                                    <span class="notification-indecator"></span>
                                </figure>
                            </a>
                        </div>

                        <ProfileDropdown :user="user" />
                    </div>
                </div>
            </div>
            <div class="dashboard-inner-wrap">
                <provider-sidebar @toggled="toggleDashboard" :isOpen="isOpen" />
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <form @submit.prevent="submit">
                            <div class="bookings-sec">
                                <div class="booking-content">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Service Name</th>
                                                <th>URL</th>
                                            </tr>
                                        </thead>
    
                                        <tbody>
                                            <tr v-for="link in socialLinks" :key="link">
                                                <td style="font-size: 18px;">{{ link }}</td>
                                                <td>
                                                    <input type="text" v-model="form.links[link]">
                                                    <span v-if="form.errors[`links.${socialLinks.indexOf(link)}.url`]" class="text-danger float-start mt-2">
                                                        {{ form.errors[`links.${socialLinks.indexOf(link)}.url`] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="form-error text-danger" v-show="form.errors.links">
                                    <p v-text="`Please provide atleast one social link`"></p>
                                </div>
                                <button type="submit" class="primary-btn mt-3">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="sidebar-overlay" @click="toggleDashboard"></div>
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