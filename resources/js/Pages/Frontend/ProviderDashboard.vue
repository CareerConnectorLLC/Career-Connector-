<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { useHead } from "@vueuse/head";
import { useMessageUpdater } from "../../composables/useMessageUpdater.js";
import ProfileDropdown from "../../components/frontend/provider/ProfileDropdown.vue";
import SideNavigation from "../../components/frontend/provider/SideNavigation.vue";
import BookingSection from "../../components/frontend/customer/dashboard/BookingSection.vue";
import PaymentSection from "../../components/frontend/customer/dashboard/PaymentSection.vue";
import PaymentSummaryCards from "../../components/frontend/provider/dashboard/PaymentSummaryCards.vue"; // Add this line
import MessageListComponent from '../../components/MessageListComponent.vue';

const page = usePage()
const scrollY = ref(0);
const isFixed = ref(false);

const searchQuery = ref('');
const isOpen = ref(false);

useHead({
    title: page.props.pageTitle,
});

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const user = computed(() => page.props.auth.user)
const bookings = computed(() => page.props.bookings)
const conversations = ref(page.props.conversations)
const payments = computed(() => page.props.payments)

const totalEarnings = computed(() => page.props.totalEarnings)
const pendingPayments = computed(() => page.props.pendingPayments)
const completedPayments = computed(() => page.props.completedPayments)

// Set up the real-time message listener and updater.
useMessageUpdater(conversations);

const filteredConversations = computed(() => {
    if (!searchQuery.value) {
        return conversations.value;
    }
    return conversations.value.filter(conversation =>
        conversation.customer.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
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
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>

                <h1>Dashboard</h1>
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
                        <div class="dashboard-inner-wrap">
                            <div class="dashboard-inner-left">
                                <div class="dashboard-inner-left-wrap">
                                    <PaymentSummaryCards :totalEarnings="totalEarnings" :pendingPayments="pendingPayments" :completedPayments="completedPayments" />

                                    <!-- Booking Section -->
                                    <BookingSection :bookings="bookings" :isCustomer="false" />
                                    <!-- Booking Section -->

                                    <!-- Payment History Section -->
                                    <PaymentSection :payments="payments" :isCustomer="false" />
                                    <!-- Payment History Section -->
                                </div>
                            </div>
                            <div class="dashboard-inner-right">
                                <div class="dashboard-message">
                                    <div class="message-card">
                                        <div class="card-head">
                                            <div class="bookings-heading">
                                                <h4>Messages</h4>
                                                <Link class="view-all" href="/messaging">View all</Link>
                                            </div>
                                            <div class="message-search">
                                                <form>
                                                    <div class="form-input">
                                                        <input type="text" placeholder="Search" v-model="searchQuery">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <MessageListComponent :conversations="filteredConversations" :role="user.role"/>
                                    </div>
                                </div>
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