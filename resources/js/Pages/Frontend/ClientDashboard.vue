<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { useMessageUpdater } from "../../composables/useMessageUpdater.js";
import ProfileDropdown from "../../components/frontend/customer/ProfileDropdown.vue";
import CustomerSidebar from "../../components/frontend/customer/LeftSidebar.vue";
import BookingSection from "../../components/frontend/customer/dashboard/BookingSection.vue";
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

const searchQuery = ref('');

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
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler">
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
                        <div class="dashboard-inner-wrap">
                            <div class="dashboard-inner-left">
                                <div class="dashboard-inner-left-wrap">
                                    <div class="row dashbaord-row">
                                        <div class="col-lg-4 col-md-6 dashbaord-col">
                                            <div class="dashbaord-booking-card">
                                                <div class="dashbaord-bookign-card-wrap">
                                                    <div class="dashboard-booking-cont">
                                                        <h2>Total active booking</h2>
                                                        <p class="count sky">10</p>
                                                    </div>
                                                    <div class="dashboard-booking-image sky">
                                                        <figure>
                                                            <img src="/public/frontend_assets/images/calendar-tick.svg"
                                                                alt="calendar-tick">
                                                        </figure>
                                                    </div>
                                                </div>
                                                <p>Last updated on Mar 05, 2023</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 dashbaord-col">
                                            <div class="dashbaord-booking-card">
                                                <div class="dashbaord-bookign-card-wrap">
                                                    <div class="dashboard-booking-cont">
                                                        <h2>Cancelled bookings</h2>
                                                        <p class="count lite-orange">50</p>
                                                    </div>
                                                    <div class="dashboard-booking-image lite-orange">
                                                        <figure>
                                                            <img src="/public/frontend_assets/images/calendar-remove.svg"
                                                                alt="calendar-remove">
                                                        </figure>
                                                    </div>
                                                </div>
                                                <p>Last updated on Mar 05, 2023</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 dashbaord-col">
                                            <div class="dashbaord-booking-card">
                                                <div class="dashbaord-bookign-card-wrap">
                                                    <div class="dashboard-booking-cont">
                                                        <h2>Total listed clients</h2>
                                                        <p class="count green">1000</p>
                                                    </div>
                                                    <div class="dashboard-booking-image green">
                                                        <figure>
                                                            <img src="/public/frontend_assets/images/user-tick.svg"
                                                                alt="user-tick">
                                                        </figure>
                                                    </div>
                                                </div>
                                                <p>Last updated on Mar 05, 2023</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Booking Section -->
                                    <template v-if="bookings.length">
                                        <BookingSection :bookings="bookings" :isCustomer="true" />
                                    </template>
                                    <!-- Booking Section -->

                                    <div class="bookings-sec">
                                        <div class="bookings-heading">
                                            <h3>Payment history</h3>
                                            <a class="view-all" href="">View all</a>
                                        </div>
                                        <div class="booking-content">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Booking id</th>
                                                        <th>Payment via</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Time</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            #00051
                                                        </td>
                                                        <td>
                                                            Perspiciatis
                                                        </td>
                                                        <td>
                                                            <span class="tag">Paid</span>
                                                        </td>
                                                        <td>
                                                            Aug 14, 2025
                                                        </td>
                                                        <td>
                                                            12:35
                                                        </td>
                                                        <td>
                                                            <a class="cmn-gray-btn"
                                                                href="">View Details</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            #00051
                                                        </td>
                                                        <td>
                                                            Perspiciatis
                                                        </td>
                                                        <td>
                                                            <span class="tag">paid</span>
                                                        </td>
                                                        <td>
                                                            Aug 14, 2025
                                                        </td>
                                                        <td>
                                                            12:35
                                                        </td>
                                                        <td>
                                                            <a class="cmn-gray-btn"
                                                                href="">View Details</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            #00051
                                                        </td>
                                                        <td>
                                                            Perspiciatis
                                                        </td>
                                                        <td>
                                                            <span class="tag pending">Pending</span>
                                                        </td>
                                                        <td>
                                                            Aug 14, 2025
                                                        </td>
                                                        <td>
                                                            12:35
                                                        </td>
                                                        <td>
                                                            <a class="cmn-gray-btn"
                                                                href="">View Details</a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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