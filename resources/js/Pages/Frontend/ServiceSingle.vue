<script setup>
import { onMounted, onUnmounted, ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { FormatMoney } from 'format-money-js';

import ProfileDropdown from "../../components/frontend/provider/ProfileDropdown.vue";
import ProviderSidebar from "../../components/frontend/provider/SideNavigation.vue";

const page = usePage();
const user = computed(() => page.props.user);
const service = page.props.service;

const scrollY = ref(0);
const isFixed = ref(false);

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$'
});

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
</script>

<template>
    <div class="dashboard-sec">
        <div class="dashboard-container">
            <div class="dashboard-head" :class="{'fixed': isFixed}">
                <button class="dashboard-toggler">
                    <span class="stick"></span>
                </button>
                <h1>Service details</h1>
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
                        <ProfileDropdown :user="page.props.auth.user" />
                    </div>
                </div>
            </div>
            <div class="dashboard-inner-wrap">
                <!-- Left sidebar panel -->
                <ProviderSidebar />
                <!-- Left sidebar panel -->
                <div class="dashboard-right-panel">
                    <div class="dashboard-right-inner">
                        <div class="dashboard-inner-left-wrap">

                            <div class="btn-wrap">
                                <Link href="/provider-profile">Back</Link>
                            </div>

                            <div class="provider-service-card">
                                <h2>Service information</h2>
                                <div class="provider-service-wrapper row">
                                    <div class="col-md-4 service-col">
                                        <figure v-if="service.file_path">
                                            <img :src="service.file_path" alt="provider-service-image">
                                        </figure>
                                        <figure v-else>
                                            <img src="/public/frontend_assets/images/provider-service-image.png"
                                                alt="provider-service-image">
                                        </figure>
                                    </div>

                                    <div class="col-md-8 service-col">
                                        <div class="provider-srecvice-inner-cont">
                                            <h3>{{ service.service.name }}</h3>
                                            <p class="price">{{ fm.from(parseInt(service.price/100)) }}</p>
                                            <p v-text="service.description"></p>
                                            <div class="dot-option d-none">
                                                <a class="notification-option-btn dropdown-toggle" href="#url"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span><img src="/public/frontend_assets/images/dots.svg" alt="dots"></span>
                                                </a>
                                                <div class="dots-drop-dowm dropdown-menu">
                                                    <ul>
                                                        <li><a href="#url">Service</a></li>
                                                        <li><a href="#url">Service</a></li>
                                                        <li><a href="#url">Service</a></li>
                                                        <li><a href="#url">Service</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-overlay"></div>
            </div>
        </div>
    </div>
</template>