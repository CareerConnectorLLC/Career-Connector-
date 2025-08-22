<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    bookings: {
        type: Array,
        required: true
    },
    isCustomer: {
        type: Boolean,
        required: true
    }
})

function formatDateTime(params) {
    return new Date(params).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        hour12: true
    });
}
</script>

<template>
    <div class="bookings-sec">
        <div class="bookings-heading">
            <h3>Bookings</h3>
            <Link class="view-all" :href="`${isCustomer ? '/bookings' : '/booking-request'}`">View all</Link>
        </div>
        <div class="booking-content">
            <table>
                <thead>
                    <tr>
                        <th>Booking id</th>
                        <th>{{ isCustomer ? 'Provider' : 'Client' }} Name</th>
                        <th>Service</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="booking in bookings" :key="booking.id">
                        <td>
                            {{ booking.booking_number }}
                        </td>
                        <td>
                            {{ isCustomer ? booking.provider.name : booking.client.name }}
                        </td>
                        <td>
                            {{ booking.service.name }}
                        </td>
                        <td>
                            <span class="tag">{{ booking.status }}</span>
                        </td>
                        <td>
                            {{ formatDateTime(booking.start_date) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>