<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { useHead } from "@vueuse/head";
import dayjs from 'dayjs';

import { useGlobalMessageNotifier } from "../../../composables/useGlobalMessageNotifier";
import { usePresenceChannel } from "../../../composables/usePresenceChannel";

import ProfileDropdown from "../../../components/frontend/customer/ProfileDropdown.vue";
import SideNavigation from "../../../components/frontend/provider/SideNavigation.vue";
import Pagination from "../../../components/frontend/Pagination.vue";

const page = usePage()
const user = computed(() => page.props.auth.user)
const bookings = computed(() => page.props.bookings)

const scrollY = ref(0);
const isFixed = ref(false);
const isOpen = ref(false);

// Initialize real-time listeners for notifications and presence.
useGlobalMessageNotifier();
usePresenceChannel();

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

useHead({
    title: page.props.pageTitle
})

function handleScroll() {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

function formatDateTime(dateTimeString) {
    return dayjs(dateTimeString).format('MMM D, YYYY h:mm A');
}

function cancelRequest(id) {
    router.patch(`/booking-request/${id}`, {
        status: 'Cancelled'
    }, {
        preserveState: true,
        onSuccess: (page) => {
            router.reload({ only: ['bookings'] })
        }
    })
}

function acceptRequest(id) {
    router.patch(`/booking-request/${id}`, {
        status: 'Confirmed'
    }, {
        preserveState: true,
        onSuccess: (page) => {
            router.reload({ only: ['bookings'] })
        }
    })
}

function isPastDate(dateTimeString) {
    return dayjs(dateTimeString).isBefore(dayjs(), 'day');
}

function toggleDashboard() {
    isOpen.value = !isOpen.value;
    document.body.classList.toggle('open-sidebar', isOpen.value);
    document.documentElement.classList.toggle('open-sidebar', isOpen.value);
}
</script>

<template>
    <div class="dashboard-sec bookings">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>
                <h1>Received request</h1>
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

                        <!-- Profile Dropdown -->
                        <ProfileDropdown :user="user" />
                    </div>
                </div>
            </div>
            <div class="dashboard-inner-wrap">
                <!-- Left sidebar for provider -->
                <SideNavigation @toggled="toggleDashboard" :class="{'open': isOpen}" />
                <!-- Left sidebar for provider -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <p class="lead" v-if="!bookings.data.length">No Data Available</p>
                        <div class="bookings-sec" v-else>
                            <div class="booking-content">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Client Name</th>
                                            <th>Booking ID</th>
                                            <th>Service </th>
                                            <th>Proposed Time</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="booking in bookings.data" :key="booking.id">
                                            <td v-text="booking.client.name"></td>
                                            <td v-text="booking.booking_number"></td>
                                            <td v-text="booking.service.name"></td>
                                            <td v-text="formatDateTime(booking.start_date)"></td>
                                            <td>
                                                <span class="tag">{{ booking.status }}</span>
                                            </td>
                                            <td>
                                                <ul class="right-recived" v-if="!isPastDate(booking.start_date) && booking.status === 'Pending'">
                                                    <li>
                                                        <a href="" title="Decline" @click.prevent="cancelRequest(booking.id)" class="crs-img">
                                                            <img src="/public/frontend_assets/images/crs-svg.svg" alt="icon">
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="" title="Accept" @click.prevent="acceptRequest(booking.id)" class="right-img">
                                                            <img src="/public/frontend_assets/images/right-svg.svg" alt="icon">
                                                        </a>
                                                    </li>
                                                </ul>
                                                <Link v-if="!isPastDate(booking.start_date) && booking.status === 'Confirmed' && booking.status !== 'Completed'" class="cmn-gray-btn" :href="`/meeting/${booking.id}`">
                                                    Start Meeting
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <Pagination :pagination="bookings" />
                        </div>
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