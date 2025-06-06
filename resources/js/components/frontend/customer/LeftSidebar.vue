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
                    <Link class="dashboard-logo" href="/client-dashboard">
                        <img src="/public/frontend_assets/images/dashboard-logo.png" alt="dashboard-logo">
                    </Link>
                </div>
                <div class="menu-wrap">
                    <ul>
                        <li :class="{ 'active': $page.url.startsWith('/client-dashboard') }">
                            <Link href="/">
                                <figure>
                                    <img src="/public/frontend_assets/images/dashboard-icon.svg" alt="dashboard-icon">
                                </figure>
                                <span>Dashboard</span>
                            </Link>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/message-notif.svg" alt="message-notif">
                                </figure>
                                <span>Messaging</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/document.svg" alt="document">
                                </figure>
                                <span>Bookings</span>
                            </a>
                        </li>
                        <li>
                            <a href="">
                                <figure>
                                    <img src="/public/frontend_assets/images/wallet-minus.svg" alt="wallet">
                                </figure>
                                <span>Payment history</span>
                            </a>
                        </li>
                        <li :class="{ 'active': $page.url.startsWith('/client-profile') }">
                            <Link href="/client-profile">
                                <figure>
                                    <img src="/public/frontend_assets/images/user-square.svg" alt="user">
                                </figure>
                                <span>My profile</span>
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