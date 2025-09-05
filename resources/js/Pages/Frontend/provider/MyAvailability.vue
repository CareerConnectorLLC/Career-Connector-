<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { useHead } from '@vueuse/head'
import ProviderSidebar from '../../../components/frontend/provider/SideNavigation.vue'
import ProfileDropdown from '../../../components/frontend/provider/ProfileDropdown.vue'
import Calendar from 'primevue/calendar'; // Import PrimeVue Calendar

import { useGlobalMessageNotifier } from '../../../composables/useGlobalMessageNotifier';
import { usePresenceChannel } from '../../../composables/usePresenceChannel';

// Initialize real-time listeners for notifications and presence.
useGlobalMessageNotifier();
usePresenceChannel();

const page = usePage()
const user = page.props.auth.user
const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']

const scrollY = ref(0);
const isFixed = ref(false);

useHead({
    title: page.props.pageTitle,
})

onMounted(() => {
    window.addEventListener('scroll', handleScroll); 
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

function handleScroll() {
    scrollY.value = window.scrollY;
    isFixed.value = scrollY.value > 0;
}

// Helper function to convert HH:MM string to Date object
const timeStringToDate = (timeString) => {
    if (!timeString) return null;
    const [hours, minutes] = timeString.split(':').map(Number);
    const date = new Date();
    date.setHours(hours, minutes, 0, 0);
    return date;
};

// Helper function to convert Date object to HH:MM string
const dateToTimeString = (date) => {
    if (!date) return '';
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${hours}:${minutes}`;
};

// Initialize form with existing availability or empty strings
const providerAvailability = page.props.availability || [];

const form = useForm({
    availability: days.reduce((acc, day) => {
        const existingDay = providerAvailability.find(item => item.day === day);
        acc[day] = {
            from: existingDay ? timeStringToDate(existingDay.from_time) : null,
            to: existingDay ? timeStringToDate(existingDay.to_time) : null
        };
        return acc;
    }, {})
});

function submit() {
    form.transform(data => ({
        availability: Object.entries(data.availability)
            .map(([day, times]) => ({
                day,
                from_time: dateToTimeString(times.from),
                to_time: dateToTimeString(times.to)
            }))
    })).post('/my-availability');
}
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container" :class="{'fixed': isFixed}">
            <div class="dashboard-head">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>
                <h1>Manage Timings</h1>
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
                        <ProfileDropdown :user="user" />
                    </div>
                </div>
            </div>
            <div class="dashboard-inner-wrap">
                <ProviderSidebar />
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <form @submit.prevent="submit">
                            <div class="bookings-sec">
                                <div class="booking-content manage-table">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="day in days" :key="day">
                                                <td>{{ day }}</td>
                                                <td>
                                                    <Calendar
                                                        v-model="form.availability[day].from"
                                                        showTime
                                                        hourFormat="24"
                                                        :stepMinute="60"
                                                        timeOnly
                                                    />
                                                    <div v-if="form.errors[`availability.${day}.from_time`]" class="text-danger text-sm mt-2 error-message">
                                                        {{ form.errors[`availability.${day}.from_time`] }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <Calendar
                                                        v-model="form.availability[day].to"
                                                        showTime
                                                        hourFormat="24"
                                                        :stepMinute="60"
                                                        timeOnly
                                                    />
                                                    <div v-if="form.errors[`availability.${day}.to_time`]" class="text-danger text-sm mt-2 error-message">
                                                        {{ form.errors[`availability.${day}.to_time`] }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-if="form.errors.availability" class="text-danger text-sm mt-2 ms-3 error-message">
                                        {{ form.errors.availability }}
                                    </div>
                                </div>
                                <button type="submit" class="primary-btn mt-3" :disabled="form.processing">Save Changes</button>
                            </div>
                        </form>
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
    </div>
</template>