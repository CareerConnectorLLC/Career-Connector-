<script setup>
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    isOpen: Boolean,
})

const signout = () => {
    router.post(`/logout`)
}

const emit = defineEmits(['toggled'])
</script>

<template>
    <div class="dashboard-left-panel">
        <div class="dashboard-inner">
            <button class="dashboard-toggler" @click="$emit('toggled')" :class="{'open': props.isOpen}">
                <span class="stick"></span>
            </button>
            <div class="dashboard-card">
                <div class="dashboard-logo-wrap">
                    <Link class="dashboard-logo" href="/">
                        <img src="/public/frontend_assets/images/dashboard-logo.png" alt="dashboard-logo">
                    </Link>
                </div>

                <div class="menu-wrap">
                    <ul>
                        <li :class="{ 'active': $page.url.startsWith('/provider-dashboard') }">
                            <Link href="/provider-dashboard">
                                <figure>
                                    <img src="/public/frontend_assets/images/dashboard-icon.svg" alt="dashboard-icon">
                                </figure>
                                <span>Dashboard</span>
                            </Link>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/booking-request') }">
                            <Link href="/booking-request">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-img-5.svg" alt="message-notif">
                                </figure>
                                <span>Received request</span>
                            </Link>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/messaging') }">
                            <Link href="/messaging">
                                <figure>
                                    <img src="/public/frontend_assets/images/document.svg" alt="document">
                                </figure>
                                <span>Messaging</span>
                            </Link>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/my-availability') }">
                            <Link href="/my-availability">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-logo-1.svg" alt="wallet">
                                </figure>
                                <span>Manage timings</span>
                            </Link>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/completed-jobs') }">
                            <Link href="/completed-jobs">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-logo-2.svg" alt="user">
                                </figure>
                                <span>Job completion</span>
                            </Link>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/social-links') }">
                            <Link href="/social-links">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-logo-2.svg" alt="user">
                                </figure>
                                <span>Social Links</span>
                            </Link>
                        </li>
                        <li class="d-none">
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/reviewsimg.svg" alt="user">
                                </figure>
                                <span>Reviews &amp; ratings</span>
                            </a>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/my-bookings') }">
                            <Link href="/my-bookings">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-img-3.svg" alt="user">
                                </figure>
                                <span>Bookings</span>
                            </Link>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/payment-history') }">
                            <Link href="/payment-history">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-img-3.svg" alt="user">
                                </figure>
                                <span>Payment history</span>
                            </Link>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/provider-profile') }">
                            <Link href="/provider-profile">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-img-4.svg" alt="user">
                                </figure>
                                <span>My Profile</span>
                            </Link>
                        </li>
                        <li>
                            <a href="" @click.prevent="signout">
                                <figure>
                                    <img src="/public/frontend_assets/images/logout.svg" alt="logout">
                                </figure>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>