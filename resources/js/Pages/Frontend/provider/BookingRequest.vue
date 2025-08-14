<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import dayjs from 'dayjs';

import { useGlobalMessageNotifier } from "../../../composables/useGlobalMessageNotifier";
import { usePresenceChannel } from "../../../composables/usePresenceChannel";

import ProfileDropdown from "../../../components/frontend/customer/ProfileDropdown.vue";
import SideNavigation from "../../../components/frontend/provider/SideNavigation.vue";

const page = usePage()
const user = computed(() => page.props.auth.user)
const bookings = computed(() => page.props.bookings)

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
</script>

<template>
    <div class="dashboard-sec bookings">
        <div class="dashboard-container">
            <div class="dashboard-head">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>
                <h1>Received request</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="nofication">
                            <a href="provider-notification.html">
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
                <SideNavigation />
                <!-- Left sidebar for provider -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="bookings-sec">
                            <p class="lead" v-if="!bookings.length">Lorem ipsum dolor sit amet consectetur.</p>
                            <div class="booking-content" v-else>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Client Name</th>
                                            <th>Booking ID</th>
                                            <th>Service </th>
                                            <th>Proposed Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="booking in bookings" :key="booking.id">
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
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                </div>
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