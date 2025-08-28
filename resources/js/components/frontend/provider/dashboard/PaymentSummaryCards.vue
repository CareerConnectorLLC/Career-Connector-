<script setup>
import { defineProps } from 'vue';
import { FormatMoney } from "format-money-js";

const fm = new FormatMoney({
    symbol: '$',
    decimals: 0
});

defineProps({
    totalEarnings: {
        type: Number,
        required: true,
    },
    pendingPayments: {
        type: Number,
        required: true,
    },
    completedPayments: {
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
                        <h2>Total Earnings</h2>
                        <p class="count sky">{{ fm.from(parseInt(totalEarnings / 100)) }}</p>
                    </div>
                    <div class="dashboard-booking-image sky">
                        <figure>
                            <img src="/public/frontend_assets/images/wallet-1.svg" alt="calendar-tick">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 dashbaord-col">
            <div class="dashbaord-booking-card">
                <div class="dashbaord-bookign-card-wrap">
                    <div class="dashboard-booking-cont">
                        <h2>Pending Payments</h2>
                        <p class="count dark-orange">{{ fm.from(parseInt(pendingPayments / 100)) }}</p>
                    </div>
                    <div class="dashboard-booking-image dark-orange">
                        <figure>
                            <img src="/public/frontend_assets/images/wallet-2.svg" alt="calendar-remove">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 dashbaord-col">
            <div class="dashbaord-booking-card">
                <div class="dashbaord-bookign-card-wrap">
                    <div class="dashboard-booking-cont">
                        <h2>Completed Payments</h2>
                        <p class="count green">{{ fm.from(parseInt(completedPayments / 100)) }}</p>
                    </div>
                    <div class="dashboard-booking-image green">
                        <figure>
                            <img src="/public/frontend_assets/images/wallet-3.svg" alt="user-tick">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>