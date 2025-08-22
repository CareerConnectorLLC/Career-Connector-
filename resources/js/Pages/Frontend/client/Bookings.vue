<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import dayjs from 'dayjs';
import { useGlobalMessageNotifier } from "../../../composables/useGlobalMessageNotifier";
import { usePresenceChannel } from "../../../composables/usePresenceChannel";

import ProfileDropdown from "../../../components/frontend/customer/ProfileDropdown.vue";
import CustomerSidebar from "../../../components/frontend/customer/LeftSidebar.vue";

const page = usePage()
const scrollY = ref(0);
const isFixed = ref(false);

// Initialize real-time listeners for notifications and presence.
useGlobalMessageNotifier();
usePresenceChannel();

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const user = computed(() => page.props.auth.user)
const bookings = computed(() => page.props.bookings)

function handleScroll() {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

function formatDateTime(dateTimeString) {
    return dayjs(dateTimeString).format('MMM D, YYYY h:mm A');
}

function isPastDate(dateTimeString) {
    return dayjs(dateTimeString).isBefore(dayjs(), 'day');
}
</script>

<template>
    <div class="dashboard-sec bookings">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>

                <h1>Bookings</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="search-form d-none">
                            <form>
                                <div class="search-inner">
                                    <div class="search-inner-wrap">
                                        <input type="text" placeholder="Search">
                                        <a class="search-filter-button" href="client-dashboard-search-filter.html"><img
                                                src="/public/frontend_assets/images/search-icon.svg" alt="search-icon"></a>
                                    </div>
                                </div>
                            </form>
                            <a href="#url" class="sarch-toggler">
                                <img src="/public/frontend_assets/images/d-search-normal.svg" alt="search-normal">
                            </a>
                        </div>

                        <div class="nofication">
                            <a href="client-notification.html">
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
                <CustomerSidebar />
                <!-- Left sidebar panel -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="bookings-sec">
                            <p class="lead fw-semibold" v-if="!bookings.length">No Data Available</p>
                            <div class="booking-content" v-else>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Provider</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="booking in bookings" :key="booking.id">
                                            <td>
                                                {{ booking.booking_number }}
                                            </td>
                                            <td>
                                                {{ booking.provider.name }}
                                            </td>
                                            <td>
                                                {{ booking.service.name }}
                                            </td>
                                            <td>
                                                <span class="tag pending">{{ booking.status }}</span>
                                            </td>
                                            <td>
                                                {{ formatDateTime(booking.start_date) }}
                                            </td>
                                            <td v-if="!isPastDate(booking.start_date) && booking.status === 'Confirmed' && booking.status !== 'Completed'">
                                                <Link class="cmn-gray-btn" :href="`/meeting/${booking.id}`">
                                                    Start Meeting
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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