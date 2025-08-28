<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { useMessageUpdater } from "../../composables/useMessageUpdater.js";
import ProfileDropdown from "../../components/frontend/customer/ProfileDropdown.vue";
import CustomerSidebar from "../../components/frontend/customer/LeftSidebar.vue";
import BookingSection from "../../components/frontend/customer/dashboard/BookingSection.vue";
import BookingSummaryCards from "../../components/frontend/customer/dashboard/BookingSummaryCards.vue"; // Add this line
import PaymentSection from "../../components/frontend/customer/dashboard/PaymentSection.vue";
import MessageListComponent from '../../components/MessageListComponent.vue';

const page = usePage()
const scrollY = ref(0);
const isFixed = ref(false);

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

const activeBookings = computed(() => page.props.activeBookings)
const cancelledBookings = computed(() => page.props.cancelledBookings)
const totalBookings = computed(() => page.props.totalBookings)

const searchQuery = ref('');
const isOpen = ref(false);

// Set up the real-time message listener and updater.
useMessageUpdater(conversations);

const filteredConversations = computed(() => {
    if (!searchQuery.value) {
        return conversations.value;
    }
    return conversations.value.filter(conversation =>
        conversation.provider.name.toLowerCase().includes(searchQuery.value.toLowerCase())
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
        <div class="dashboard-container" :class="{'fixed': isFixed}">
            <div class="dashboard-head">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>
                <h1>Dashboard</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="search-form d-none">
                            <form>
                                <div class="search-inner">
                                    <div class="search-inner-wrap">
                                        <input type="text" placeholder="Search">
                                        <a class="search-filter-button" href="">
                                            <img src="/public/frontend_assets/images/search-icon.svg" alt="search-icon">
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <a href="#url" class="sarch-toggler">
                                <img src="/public/frontend_assets/images/d-search-normal.svg" alt="search-normal">
                            </a>
                        </div>

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
                <CustomerSidebar @toggled="toggleDashboard" :isOpen="isOpen" />
                <!-- Left sidebar panel -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="dashboard-inner-wrap">
                            <div class="dashboard-inner-left">
                                <div class="dashboard-inner-left-wrap">
                                    <BookingSummaryCards :activeBookings="activeBookings" :cancelledBookings="cancelledBookings" :totalBookings="totalBookings" />

                                    <!-- Booking Section -->
                                    <template v-if="bookings.length">
                                        <BookingSection :bookings="bookings" :isCustomer="true" />
                                    </template>
                                    <!-- Booking Section -->

                                    <PaymentSection :payments="payments" :isCustomer="true" />
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
                                    <MessageListComponent :conversations="filteredConversations" :role="user.role" />
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