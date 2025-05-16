<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import ProfileDropdown from "../../components/frontend/provider/ProfileDropdown.vue";
import SideNavigation from "../../components/frontend/provider/SideNavigation.vue";

const page = usePage()
const user = computed(() => page.props.auth.user)
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>

                <h1>Dashboard</h1>
                <div class="search-sec">
                    <div class="serach-inner-wrap">
                        <div class="nofication">
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
                <SideNavigation />
                <!-- Left sidebar for provider -->
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
                                                        <h2>Total Earnings</h2>
                                                        <p class="count sky">$450</p>
                                                    </div>
                                                    <div class="dashboard-booking-image sky">
                                                        <figure>
                                                            <img src="/public/frontend_assets/images/wallet-1.svg" alt="calendar-tick">
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
                                                        <h2>Pending Payments</h2>
                                                        <p class="count dark-orange">$50</p>
                                                    </div>
                                                    <div class="dashboard-booking-image dark-orange">
                                                        <figure>
                                                            <img src="/public/frontend_assets/images/wallet-2.svg" alt="calendar-remove">
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
                                                        <h2>Completed Payments</h2>
                                                        <p class="count green">$350</p>
                                                    </div>
                                                    <div class="dashboard-booking-image green">
                                                        <figure>
                                                            <img src="/public/frontend_assets/images/wallet-3.svg" alt="user-tick">
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
                                                        <th>Client name</th>
                                                        <th>Service</th>
                                                        <th>Location</th>
                                                        <th>Proposed time</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            Jenny Wilson
                                                        </td>
                                                        <td>
                                                            Perspiciatis
                                                        </td>
                                                        <td>
                                                            4517 Washington Ave. Manchester...
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
                                                            Esther Howard
                                                        </td>
                                                        <td>
                                                            Perspiciatis
                                                        </td>
                                                        <td>
                                                            6391 Elgin St. Celina, Delaware 10299
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
                                                            Jane Cooper
                                                        </td>
                                                        <td>
                                                            Perspiciatis
                                                        </td>
                                                        <td>
                                                            70 Washington Square South, New York,...
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
                                                            Wade Warren
                                                        </td>
                                                        <td>
                                                            Perspiciatis
                                                        </td>
                                                        <td>
                                                            3891 Ranchview Dr. Richardson...
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
                                                        <th>Booking ID</th>
                                                        <th>Payment via</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Amount</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            #00051
                                                        </td>
                                                        <td>
                                                            Stripe
                                                        </td>
                                                        <td>
                                                            <span class="tag">Paid</span>
                                                        </td>
                                                        <td>
                                                            Aug 14, 2025
                                                        </td>
                                                        <td>
                                                            $40
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
                                                            Paypal
                                                        </td>
                                                        <td>
                                                            <span class="tag">paid</span>
                                                        </td>
                                                        <td>
                                                            Aug 14, 2025
                                                        </td>
                                                        <td>
                                                            $100
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
                                                            Stripe
                                                        </td>
                                                        <td>
                                                            <span class="tag">Paid</span>
                                                        </td>
                                                        <td>
                                                            Aug 14, 2025
                                                        </td>
                                                        <td>
                                                            $150
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
                                                <a class="view-all" href="">View all</a>
                                            </div>
                                            <div class="message-search">
                                                <form>
                                                    <div class="form-input">
                                                        <input type="text" placeholder="Search">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="message-wrap">
                                            <ul>
                                                <li>
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-01.png"
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
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-02.png"
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
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-03.png"
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
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-04.png"
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
                                                    <a href="">
                                                        <figure>
                                                            <img src="/public/frontend_assets/images/message-profile-08.png"
                                                                alt="message-profile">
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
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-09.png"
                                                                alt="message-profile"></figure>
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
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-05.png"
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
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-06.png"
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
                                                    <a href="">
                                                        <figure><img src="/public/frontend_assets/images/message-profile-07.png"
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
                <div class="change-passpord" id="logout-sec" style="display: none;">
                    <div class="logout-wrap">
                        <h2>Logout?</h2>
                        <p>Are you sure you want to logout</p>
                        <div class="l-btn-wrap">
                            <a href="#url" class="outline-btn">Yes</a>
                            <a href="#url" data-fancybox-close="" class="primary-btn">No</a>
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