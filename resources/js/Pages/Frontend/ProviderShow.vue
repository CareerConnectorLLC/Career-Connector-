<script setup>
import { computed, ref, onMounted } from 'vue'
import { usePage, Link, router } from '@inertiajs/vue3'
import { FormatMoney } from 'format-money-js'
import { Modal } from 'bootstrap'

import BookingModal from '../../components/frontend/BookingModal.vue'
import NoCardModal from '../../components/frontend/NoCardModal.vue'

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$'
});

const page = usePage()
const modalShown = ref(false)
const bookingModal = ref(null)
const noCardModal = ref(null)
const redirectToBookingsAfterClose = ref(false)

const provider = computed(() => page.props.provider)
const hasBooking = computed(() => page.props.has_pending_or_confirmed_booking_today)

onMounted(() => {
    const bookingModalEl = document.getElementById('bookingModal')
    
    bookingModal.value = new Modal(`#bookingModal`)
    noCardModal.value = new Modal(`#noCardModal`)
    
    bookingModalEl.addEventListener('shown.bs.modal', () => {
        modalShown.value = true
    })

    bookingModalEl.addEventListener('hidden.bs.modal', () => {
        modalShown.value = false
        if (redirectToBookingsAfterClose.value) {
            router.visit('/bookings')
            redirectToBookingsAfterClose.value = false // Reset for next time
        }
    })
})

const timings = computed(() => {
    const originalAvailability = JSON.parse(page.props.timings)
    const dayOrder = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    const formattedAvailabilityArray = dayOrder.map(day => {
        const times = originalAvailability.hasOwnProperty(day) ? originalAvailability[day] : null;
        let timeRange = null;

        if (Array.isArray(times) && times.length > 0) {
            const startTime = times[0];
            const endTime = times[times.length - 1];
            timeRange = `${startTime} - ${endTime}`;
        } else if (times === null) {
            timeRange = "No Availability";
        } else if (Array.isArray(times) && times.length === 0) {
            timeRange = "No Availability";
        }

        return {
            day: day,
            timeRange: timeRange
        }
    })

    return formattedAvailabilityArray
})

const workingDaysCount = computed(() => timings.value.filter(t => t.timeRange != 'No Availability').length)

function closeBookingModal() {
    redirectToBookingsAfterClose.value = true
    bookingModal.value.hide()
}

function checkForPaymentMethods() {
    if (page.props.has_payment_methods) {
        bookingModal.value.show()
    } else {
        noCardModal.value.show()
    }
}

function goToChatRoom() {
    router.post(`/messaging`, {
            provider_id: provider.value.provider.id,
            service_id: provider.value.service.id
        },
        {
            preserveScroll: true,
        }
    )
}

function goToProfilePage() {
    noCardModal.value.hide()
    router.visit(`/client-profile`)
}

function socialIconImageUrl(name) {
    return `${window.location.origin}/frontend_assets/images/${name.toLowerCase()}.svg`
}
</script>

<template>
    <section class="banner-sec inner-banner">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                    <h1 class="text-capitalize">service provider</h1>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.
                    </p>
                    <div class="banner-image-1">
                        <img src="/public/frontend_assets/images/banner-image01.png" alt="banner-image-1">
                    </div>
                    <div class="banner-image-2">
                        <img src="/public/frontend_assets/images/banner-image02.png" alt="banner-image-2">
                    </div>
                </div>
                <img class="top-shape" src="/public/frontend_assets/images/banner-top-shape.png" alt="banner-top-shape">
                <img class="bottom-shape" src="/public/frontend_assets/images/inner-banner-right-shape.svg"
                    alt="banner-bottom-shape">
                <img class="left-blur-shape" src="/public/frontend_assets/images/banner-left-blur-shape.png"
                    alt="banner-left-blur-shape">
                <img class="right-blur-shape" src="/public/frontend_assets/images/banner-top-blur-shape.png"
                    alt="banner-top-blur-shape">
            </div>
        </div>
    </section>

    <section class="provider-details-sec">
        <div class="container">
            <div class="provider-inner">
                <div class="provider-row row">
                    <div class="col-lg-7 provider-details-col-left">
                        <div class="provider-details-left-cont">
                            <div class="provider-details-head">
                                <div class="card-profile">
                                    <div class="card-profile-img-wrap">
                                        <img v-if="!provider.provider.profile_photo_path"
                                            src="/public/frontend_assets/images/profile-image-01.png"
                                            alt="profile-image-01">
                                        <img v-else :src="provider.provider.profile_photo_url"
                                            :alt="provider.provider.name">
                                    </div>
                                    <div class="card-profile-details">
                                        <h2 v-text="provider.provider.name"></h2>
                                        <div class="ratings d-none">
                                            <ul>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                                <li>
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                </li>
                                            </ul>
                                            <p>4.9</p>
                                        </div>

                                        <div class="social-links" v-if="provider.provider.provider_social_links">
                                            <ul>
                                                <li v-for="link in provider.provider.provider_social_links" :key="link.id">
                                                    <a :href="link.url" target="_blank">
                                                        <img :src="socialIconImageUrl(link.name)" :alt="link.name.toLowerCase()">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="provider-details-tags">
                                    <div class="rated d-none">
                                        <figure><img src="/public/frontend_assets/images/profile-star.svg"
                                                alt="profile-star"></figure>
                                        <span>Top rated</span>
                                    </div>
                                    <div class="card-tags">
                                        <span class="tag">{{ provider.service.name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="provider-details-content">
                                <h3 v-text="provider.service.name"></h3>
                                <p v-text="provider.description"></p>
                            </div>

                            <div class="about-service-provider d-none">
                                <h4>About the service provider</h4>
                                <div class="service-provider-card">
                                    <div class="blur green"></div>
                                    <img class="card-shape"
                                        src="/public/frontend_assets/images/service-provider-card-shape.svg"
                                        alt="service-provider-card-shape">
                                    <div class="card-profile">
                                        <div class="card-profile-img-wrap">
                                            <img src="/public/frontend_assets/images/about-service-provider-card.png"
                                                alt="service-provider">
                                        </div>
                                        <div class="card-profile-details">
                                            <h5>Davis D’suza</h5>
                                            <div class="provider-details-tags">
                                                <div class="rated">
                                                    <figure><img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                    <span>Top rated</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="address">
                                        <ul>
                                            <li>2972 Westheimer Rd</li>
                                            <li>1 contract in progress</li>
                                        </ul>
                                    </div>
                                    <p>
                                        Pellentesque interdum felis quis dui euismod, dignissim fermentum elit
                                        elementum. Mauris malesuada eu mauris vel feugiat. Mauris vel fermentum purus.
                                        Aliquam vitae nibh pulvinar dui faucibus tempus. Pellentesque habitant morbi
                                        tristique senectus et netus et malesuada fames ac turpis egestas.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5  provider-details-col-right">
                        <div class="provider-details-col-inner">
                            <div class="provider-pricing-card">
                                <div class="provider-pricing-card-head">
                                    <h4>{{ provider.service.name }} Fee</h4>
                                    <p class="pricing">{{ fm.from(parseInt(provider.price / 100)) }}</p>
                                </div>

                                <div class="address">
                                    <h4>Address</h4>
                                    <div class="address-wrap">
                                        <figure>
                                            <img src="/public/frontend_assets/images/location-green.svg"
                                                alt="location-green">
                                        </figure>
                                        <p v-text="provider.provider?.location ?? `Not Available`"></p>
                                    </div>
                                </div>

                                <div class="duration">
                                    <div class="duration-head">
                                        <h4>Job duration</h4>
                                        <p>{{ workingDaysCount }} working days</p>
                                    </div>
                                    <template v-if="timings.length">
                                        <ul>
                                            <li v-for="(timing, index) in timings" :key="index">
                                                <span class="date">{{ timing.day }}</span>
                                                <span
                                                    :class="{'close': timing.timeRange == 'No Availability', 'timing': timing.timeRange != 'No Availability'}">
                                                    {{ timing.timeRange }}
                                                </span>
                                            </li>
                                        </ul>
                                    </template>
                                    <Link v-if="!page.props.is_auth" class="book-now" href="/login" @click.prevent="">Sign in for Booking</Link>
                                    
                                    <template v-if="page.props.is_auth && page.props.auth.user.role === 'USER'">
                                        <a href="" @click.prevent="checkForPaymentMethods" class="book-now">Book now</a>
                                        <a href="" @click.prevent="goToChatRoom" class="book-now mt-3">Start a Conversation</a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="blur green"></div>
        <!-- Modal -->
        <BookingModal
            :provider="provider.provider"
            :service="provider.service"
            :timings="JSON.parse(page.props.schedules)"
            :fees="provider.price/100"
            :modalShown="modalShown"
            v-on:booking-success="closeBookingModal"
        />
        <NoCardModal
            :customer="page.props.auth.user"
            v-on:proceed-to-add-card="goToProfilePage"
        />
    </section>
</template>