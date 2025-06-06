<script setup>
import { computed, onMounted, ref, onUnmounted } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import ProfileDropdown from "../../components/frontend/customer/ProfileDropdown.vue";
import CustomerSidebar from "../../components/frontend/customer/LeftSidebar.vue";

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
                        <div class="search-form">
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

                                    <div class="bookings-sec">
                                        <div class="bookings-heading">
                                            <h3>Bookings</h3>
                                            <a class="view-all" href="">View all</a>
                                        </div>
                                        <div class="booking-content">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Booking id</th>
                                                        <th>Service</th>
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
                                                            <a class="cmn-gray-btn" href="">View
                                                                Details</a>
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
                                                            <a class="cmn-gray-btn" href="">View Details</a>
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
                                                            <a class="cmn-gray-btn" href="">View Details</a>
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
                                                            <a class="cmn-gray-btn" href="">View Details</a>
                                                        </td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>

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
                                                <a class="view-all" href="message.html">View all</a>
                                            </div>
                                            <div class="message-search">
                                                <form>
                                                    <div class="form-input">
                                                        <input type="text" placeholder="Search">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="message-wrap dashboard-message-list">
                                            <ul>
                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/message-profile-01.png"
                                                                alt="message-profile"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Wade Warren</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/message-profile-02.png"
                                                                alt="message-profile"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Esther Howard</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                            <p class="counting">02</p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/message-profile-03.png"
                                                                alt="message-profile"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Alison D’suza</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                            <p class="counting">02</p>
                                                        </div>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/message-profile-04.png"
                                                                alt="message-profile"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Brooklyn Simmons</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/jenny-wilson-2.png"
                                                                alt="Jenny-w">
                                                        </figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Jenny Wilson</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/Kristin-Watson-2.png"
                                                                alt="Kristin-Watson"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Kristin Watson</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                            <p class="counting">02</p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/message-profile-05.png"
                                                                alt="message-profile"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Savannah Nguyen</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/message-profile-06.png"
                                                                alt="message-profile"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Cody Fisher</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="message.html">
                                                        <figure><img
                                                                src="/public/frontend_assets/images/message-profile-07.png"
                                                                alt="message-profile"></figure>
                                                        <div class="message-cont">
                                                            <div class="message-cont-head">
                                                                <h5>Annette Black</h5>
                                                                <p class="time">12:35</p>
                                                            </div>
                                                            <p>
                                                                Lorem ipsum dolor sit consectetur adipiscing.
                                                            </p>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
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