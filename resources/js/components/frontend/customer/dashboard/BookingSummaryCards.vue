<script setup>
import { defineProps } from 'vue';

defineProps({
    activeBookings: {
        type: Number,
        required: true,
    },
    cancelledBookings: {
        type: Number,
        required: true,
    },
    totalBookings: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div class="row dashbaord-row">
        <div class="col-lg-4 col-md-6 dashbaord-col">
            <div class="dashbaord-booking-card">
                <div class="dashbaord-bookign-card-wrap">
                    <div class="dashboard-booking-cont">
                        <h2>Active bookings</h2>
                        <p class="count sky">{{ activeBookings }}</p>
                    </div>
                    <div class="dashboard-booking-image sky">
                        <figure>
                            <img src="/public/frontend_assets/images/calendar-tick.svg"
                                alt="calendar-tick">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 dashbaord-col">
            <div class="dashbaord-booking-card">
                <div class="dashbaord-bookign-card-wrap">
                    <div class="dashboard-booking-cont">
                        <h2>Cancelled bookings</h2>
                        <p class="count lite-orange">{{ cancelledBookings }}</p>
                    </div>
                    <div class="dashboard-booking-image lite-orange">
                        <figure>
                            <img src="/public/frontend_assets/images/calendar-remove.svg"
                                alt="calendar-remove">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 dashbaord-col">
            <div class="dashbaord-booking-card">
                <div class="dashbaord-bookign-card-wrap">
                    <div class="dashboard-booking-cont">
                        <h2>Total Bookings</h2>
                        <p class="count green">{{ totalBookings }}</p>
                    </div>
                    <div class="dashboard-booking-image green">
                        <figure>
                            <img src="/public/frontend_assets/images/user-tick.svg"
                                alt="user-tick">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>