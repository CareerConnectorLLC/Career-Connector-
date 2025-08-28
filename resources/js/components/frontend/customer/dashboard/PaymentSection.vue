<script setup>
import { defineProps } from 'vue';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { FormatMoney } from 'format-money-js';

defineProps({
    payments: {
        type: Array,
        required: true,
    },
    isCustomer: {
        type: Boolean,
        required: true,
    },
});

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$',
});

function formatDateTime(dateTimeString) {
    if (!dateTimeString) return 'N/A';
    return dayjs(dateTimeString).format('MMM D, YYYY');
}
</script>

<template>
    <div class="bookings-sec">
        <div class="bookings-heading">
            <h3>Payment history</h3>
            <Link class="view-all" :href="isCustomer ? '/client-payment-history' : '/payment-history'">View all</Link>
        </div>
        <div class="booking-content">
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>{{ isCustomer ? 'Provider' : 'Client' }}</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="payment in payments" :key="payment.id">
                        <td>{{ payment.booking_number }}</td>
                        <td>{{ isCustomer ? payment.provider.name : payment.client.name }}</td>
                        <td>
                            <span class="tag">{{ payment.status }}</span>
                        </td>
                        <td>{{ formatDateTime(payment.updated_at) }}</td>
                        <td>{{ fm.from(payment.price/100) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>