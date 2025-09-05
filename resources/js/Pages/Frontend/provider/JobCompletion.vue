<script setup>
import { computed, ref, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { useHead } from '@vueuse/head';

import { useGlobalMessageNotifier } from '../../../composables/useGlobalMessageNotifier';
import { usePresenceChannel } from '../../../composables/usePresenceChannel';

import ProviderSidebar from '../../../components/frontend/provider/SideNavigation.vue'
import ProfileDropdown from '../../../components/frontend/provider/ProfileDropdown.vue'

// Initialize real-time listeners for notifications and presence.
useGlobalMessageNotifier();
usePresenceChannel();

const page = usePage()
const user = computed(() => page.props.auth.user)
const completedTasks = computed(() => page.props.completedTasks)
const isOpen = ref(false)
const scrollY = ref(0)
const isFixed = ref(false)

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
})

useHead({
    title: page.props.pageTitle,
})

const toggleDashboard = () => {
    isOpen.value = !isOpen.value;
    document.body.classList.toggle('open-sidebar', isOpen.value);
    document.documentElement.classList.toggle('open-sidebar', isOpen.value);
}

const handleScroll = () => {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>

<template>
    <div class="dashboard-sec bookings">
        <div class="dashboard-container" :class="{'fixed': isFixed}">
            <div class="dashboard-head">
                <button class="dashboard-toggler" @click="toggleDashboard" :class="{'open': isOpen}">
                    <span class="stick"></span>
                </button>
                <h1>Job completion</h1>
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
                <!-- Left Sidebar panel for providers -->
                <provider-sidebar @toggled="toggleDashboard" :isOpen="isOpen" />
                <!-- Left Sidebar panel for providers -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="bookings-sec">
                            <p class="lead fw-semibold" v-if="completedTasks.length === 0">No data found</p>
                            <div class="booking-content" v-else>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Client Name</th>
                                            <th>Booking ID</th>
                                            <th>Service Type</th>
                                            <th>Date</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="task in completedTasks" :key="task.id">
                                            <td v-text="task.client.name"></td>
                                            <td v-text="task.booking_number"></td>
                                            <td v-text="task.service.name"></td>
                                            <td v-text="formatDate(task.created_at)"></td>
                                            <td></td>
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
                <div class="sidebar-overlay" @click="toggleDashboard"></div>
                <div class="booking-options" id="bookingOption">

                    <div class="booking-overlay"></div>

                    <div class="change-passpord" id="change-passpord" style="display: none;">

                        <!-- <div class="booking-option-item"> -->
                        <div class="book-inner">
                            <div class="book-head">
                                <h3>Booking details</h3>
                                <p>Lorem ipsum dolor sit amet</p>
                            </div>

                            <div class="banner-option-inner-wrap no-height">
                                <div class="book-cont">
                                    <div class="provider-details-head">
                                        <div class="card-profile">
                                            <div class="card-profile-img-wrap">
                                                <img src="/public/frontend_assets/images/profile-image-01.png" alt="profile-image-01">
                                            </div>
                                            <div class="card-profile-details">
                                                <h2>Esther Howard</h2>
                                                <p class="mb-0 booking-id">Booking <span>ID #00051</span></p>
                                            </div>
                                        </div>

                                        <div class="provider-details-tags center-wrap">
                                            <p class="price">$40</p>
                                            <span class="tag">Paid</span>
                                        </div>
                                    </div>

                                    <div class="selected-fields service-four">
                                        <ul>
                                            <li>
                                                <figure><img src="/public/frontend_assets/images/location-icon.svg" alt="briefcase">
                                                </figure>
                                                <div class="selected-field-cont">
                                                    <p class="field-head">Location</p>
                                                    <p class="field-text">New York</p>
                                                </div>
                                            </li>
                                            <li>
                                                <figure><img src="/public/frontend_assets/images/status-img.svg" alt="calenda"></figure>
                                                <div class="selected-field-cont">
                                                    <p class="field-head">Status</p>
                                                    <p class="field-text">Paid</p>
                                                </div>
                                            </li>
                                            <li>
                                                <figure><img src="/public/frontend_assets/images/clock-2.svg" alt="clock"></figure>
                                                <div class="selected-field-cont">
                                                    <p class="field-head">Time</p>
                                                    <p class="field-text">12:35 </p>
                                                </div>
                                            </li>
                                            <li>
                                                <figure><img src="/public/frontend_assets/images/calendar-green.svg" alt="clock">
                                                </figure>
                                                <div class="selected-field-cont">
                                                    <p class="field-head">Date</p>
                                                    <p class="field-text">Aug 14, 2025</p>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="review-part">
                                        <p>Review</p>
                                        <div class="coma-review">
                                            <div class="flex-rev">
                                                <div class="coma-img">
                                                    <img src="/public/frontend_assets/images/coma-img.svg" alt="img">
                                                </div>
                                                <p>23.07.2023</p>
                                            </div>
                                            <div class="coma-content">
                                                <p>Donec ex risus, iaculis id turpis a, auctor lobortis arcu. </p>
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                                    eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim
                                                    ad minim veniam, quis nostrud.</p>
                                                <div class="card-profile">
                                                    <div class="card-profile-img-wrap">
                                                        <img src="/public/frontend_assets/images/profile-image-01.png"
                                                            alt="profile-image-01">
                                                    </div>
                                                    <div class="card-profile-details">
                                                        <h3>Esther Howard</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="change-passpord" id="logout-sec" style="display: none;">
                        <div class="logout-wrap">
                            <h2>Logout?</h2>
                            <p>Are you sure you want to logout</p>
                            <div class="l-btn-wrap">
                                <a href="#url" class="outline-btn">Yes</a>
                                <a href="#url" data-fancybox-close class="primary-btn">No</a>
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