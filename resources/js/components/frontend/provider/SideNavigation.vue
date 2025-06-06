<script setup>
import { Link, router } from "@inertiajs/vue3";

const signout = () => {
    router.post(`/logout`)
}
</script>

<template>
    <div class="dashboard-left-panel">
        <div class="dashboard-inner">
            <button class="dashboard-toggler">
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
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-img-5.svg" alt="message-notif">
                                </figure>
                                <span>Received request</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/document.svg" alt="document">
                                </figure>
                                <span>Message</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-logo-1.svg" alt="wallet">
                                </figure>
                                <span>Manage availability</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-logo-2.svg" alt="user">
                                </figure>
                                <span>Job completion</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/reviewsimg.svg" alt="user">
                                </figure>
                                <span>Reviews &amp; ratings</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/provider-img-3.svg" alt="user">
                                </figure>
                                <span>Payment history</span>
                            </a>
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