<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { FormatMoney } from "format-money-js";
import { useHead } from "@vueuse/head";
import dayjs from 'dayjs';

import ProfileDropdown from "../../../components/frontend/customer/ProfileDropdown.vue";
import SideNavigation from "../../../components/frontend/provider/SideNavigation.vue";

import { useGlobalMessageNotifier } from "../../../composables/useGlobalMessageNotifier";
import { usePresenceChannel } from "../../../composables/usePresenceChannel";

const fm = new FormatMoney({
    symbol: '$',
    decimals: 0
})

const page = usePage()
const user = computed(() => page.props.auth.user)
const payments = computed(() => page.props.payments)
const totalEarnings = computed(() => page.props.totalEarnings)
const selectedPayments = ref([]);

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
    title: page.props.pageTitle,
})

function handleScroll() {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

function toggleDashboard() {
    isOpen.value = !isOpen.value;
    document.body.classList.toggle('open-sidebar', isOpen.value);
    document.documentElement.classList.toggle('open-sidebar', isOpen.value);
}

function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'N/A';
    return dayjs(dateTimeString).format('MMM D, YYYY');
}

const selectedPayoutAmount = computed(() => {
    let grossSelectedAmountInDollars = 0;
    payments.value.forEach(payment => {
        if (selectedPayments.value.includes(payment.id)) {
            grossSelectedAmountInDollars += payment.price; // payment.price is already in dollars
        }
    });

    // Fetch commission percentage from page props
    const commissionPercentage = page.props.commissionPercentage ?? 0;

    // Convert to cents for commission calculation
    const grossSelectedAmountInCents = grossSelectedAmountInDollars * 100;

    const commissionAmountInCents = Math.floor((grossSelectedAmountInCents * commissionPercentage) / 100);
    const netSelectedAmountInCents = grossSelectedAmountInCents - commissionAmountInCents;

    return netSelectedAmountInCents / 100; // Return in dollars for display
});

const displayedPayoutAmount = computed(() => {
    if (selectedPayments.value.length === 0) {
        return totalEarnings.value; // totalEarnings is already net and in dollars
    } else {
        return selectedPayoutAmount.value; // selectedPayoutAmount is already net and in dollars
    }
});


function submitPayout() {
    let dataToSend = {};
    if (selectedPayments.value.length > 0) {
        dataToSend = { booking_ids: selectedPayments.value };
    }
    // If selectedPayments.value is empty, dataToSend will be an empty object,
    // which means the backend will interpret it as "all unpaid bookings".

    router.post(`/initiate-payout`, dataToSend, {
        preserveScroll: true,
        onSuccess: () => {
            selectedPayments.value = []; // Clear selection on success
            // Maybe show a success toast or modal
        },
        onError: () => {
            // Maybe show an error toast or modal
        }
    });
}
</script>

<template>
    <div class="dashboard-sec bookings">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>
                <h1>Payment History</h1>
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
                <SideNavigation @toggled="toggleDashboard" :isOpen="isOpen" />
                <!-- Left sidebar for provider -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="bookings-sec">
                            <div class="payout-summary mb-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <h3 class="h5">Available for Payout: <span class="text-success">{{ fm.from(parseInt(displayedPayoutAmount)) }}</span></h3>
                                </div>
                                <button @click="submitPayout" class="btn btn-primary" v-show="displayedPayoutAmount > 0">
                                    Withdraw Funds
                                </button>
                            </div>

                            <p class="lead" v-if="!payments.length">No payment history available.</p>
                            <div class="booking-content" v-else>
                                <table>
                                    <thead>
                                        <tr>
                                            <th></th> <!-- New column for checkbox -->
                                            <th>Booking Number</th>
                                            <th>Customer Name</th>
                                            <th>Service Name</th>
                                            <th>Service Fees</th>
                                            <th>Booking Date</th>
                                            <th>Payout Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="payment in payments" :key="payment.id">
                                            <td>
                                                <input type="checkbox" v-show="!payment.paid_out_at" :value="payment.id" v-model="selectedPayments" />
                                            </td> <!-- New column with checkbox -->
                                            <td>{{ payment.booking_number }}</td>
                                            <td>{{ payment.client.name }}</td>
                                            <td>{{ payment.service.name }}</td>
                                            <td>{{ fm.from(parseInt(payment.price)) }}</td>
                                            <td>{{ formatDateTime(payment.start_date) }}</td>
                                            <td>
                                                <span v-if="payment.paid_out_at" class="tag tag-success">{{ formatDateTime(payment.paid_out_at) }}</span>
                                                <span v-else class="tag tag-warning">Pending</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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