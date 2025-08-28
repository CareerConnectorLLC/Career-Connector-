<script setup>
import { onMounted, onUnmounted, ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import dayjs from "dayjs";
import { FormatMoney } from "format-money-js";

import { useGlobalMessageNotifier } from "../../../composables/useGlobalMessageNotifier";
import { usePresenceChannel } from "../../../composables/usePresenceChannel";
import ProviderSidebar from "../../../components/frontend/provider/SideNavigation.vue";
import ProfileDropdown from "../../../components/frontend/provider/ProfileDropdown.vue";

// Initialize real-time listeners for notifications and presence.
useGlobalMessageNotifier();
usePresenceChannel();

const fm = new FormatMoney({
    decimals: 0,
    symbol: "$"
});

const page = usePage()
const user = computed(() => page.props.auth.user)
const bookings = computed(() => page.props.bookings)

// Initialize real-time listeners for notifications and presence.
useGlobalMessageNotifier();
usePresenceChannel();

const scrollY = ref(0);
const isFixed = ref(false);
const isOpen = ref(false);

onMounted(() => {
    window.addEventListener('scroll', handleScroll); 
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

function handleScroll() {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

function toggleDashboard() {
    isOpen.value = !isOpen.value;
    document.body.classList.toggle('open-sidebar', isOpen.value);
    document.documentElement.classList.toggle('open-sidebar', isOpen.value);
}

function isPastDate(dateTimeString) {
    return dayjs(dateTimeString).isBefore(dayjs(), 'day');
}

const formatDate = (dateString) => {
    return dayjs(dateString).format('MMMM D, YYYY, h:mm A');
};
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>

                <h1>Bookings</h1>
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
                <ProviderSidebar @toggled="toggleDashboard" :isOpen="isOpen" />
                <!-- Left sidebar for provider -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="bookings-sec">
                            <div class="booking-content" v-if="bookings.length">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Booking Number</th>
                                            <th>Client name</th>
                                            <th>Service</th>
                                            <th>Date & Time</th>
                                            <th>Price</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="booking in bookings" :key="booking.id">
                                            <td v-text="booking.booking_number"></td>
                                            <td v-text="booking.client.name"></td>
                                            <td v-text="booking.service.name"></td>
                                            <td v-text="formatDate(booking.start_date)"></td>
                                            <td v-text="fm.from(parseInt(booking.price / 100))"></td>
                                            <td v-if="!isPastDate(booking.start_date) && booking.status === 'Confirmed'">
                                                <a class="cmn-gray-btn" href="#">
                                                    Start Meeting
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <p class="lead" v-else>No Data Available</p>
                            <div class="cmn-pgns d-none">
                                <ul>
                                    <li class="prev"><a href="#">Prev</a></li>
                                    <li><a href="#" class="active">01</a></li>
                                    <li><a href="#">02</a></li>
                                    <li><a href="#">03</a></li>
                                    <li><a href="#">04</a></li>
                                    <li><a href="#">05</a></li>
                                    <li class="next"><a href="#">Next</a></li>
                                </ul>
                            </div>
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