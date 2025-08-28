<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue"
import { usePage } from "@inertiajs/vue3"
import dayjs from 'dayjs'
import { FormatMoney } from 'format-money-js'

import { useGlobalMessageNotifier } from "../../../composables/useGlobalMessageNotifier";
import { usePresenceChannel } from "../../../composables/usePresenceChannel";
import Pagination from "../../../components/frontend/Pagination.vue";

import ProfileDropdown from "../../../components/frontend/provider/ProfileDropdown.vue"
import CustomerSidebar from "../../../components/frontend/customer/LeftSidebar.vue"

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$'
});

useGlobalMessageNotifier();
usePresenceChannel();

const page = usePage()
const user = computed(() => page.props.auth.user)
const bookings = computed(() => page.props.bookings)

const scrollY = ref(0);
const isFixed = ref(false);
const isOpen = ref(false);

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
})

function formatDateTime(dateTimeString) {
    return dayjs(dateTimeString).format('MMM D, YYYY h:mm A');
}

function handleScroll() {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

function toggleDashboard() {
    isOpen.value = !isOpen.value;
    document.body.classList.toggle('open-sidebar', isOpen.value);
    document.documentElement.classList.toggle('open-sidebar', isOpen.value);
}
</script>

<template>
    <div class="dashboard-sec payment">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>
                <h1>Payment history</h1>
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
                <!-- Left sidebar panel -->
                <CustomerSidebar @toggled="toggleDashboard" :class="{'open': isOpen}" />
                <!-- Left sidebar panel -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <p class="lead fw-semibold" v-if="!bookings.data.length">No Data Available</p>
                        <div class="bookings-sec" v-else>
                            <div class="booking-content">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Booking ID</th>
                                            <th>Service</th>
                                            <th>Provider</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in bookings.data" :key="item.id">
                                            <td>{{ item.booking_number }}</td>
                                            <td>{{ item.service.name }}</td>
                                            <td>{{ item.provider.name }}</td>
                                            <td>{{ fm.from(parseInt(item.price / 100)) }}</td>
                                            <td>{{ formatDateTime(item.start_date) }}</td>
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