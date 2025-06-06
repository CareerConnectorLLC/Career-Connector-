<script setup>
import { computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import { FormatMoney } from 'format-money-js'
import ProviderFilter from '../../components/frontend/ProviderFilterPanel.vue'
import Pagination from '../../components/frontend/Pagination.vue'

const page = usePage()
const services = computed(() => page.props.services)
const providers = computed(() => page.props.providers)
const serviceIds = computed(() => page.props.service_ids)
const price = computed(() => page.props.pricing)

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$'
});

const handleFilter = (param) => {
    router.get(
        `/provider-listing`, {
            services: param.services,
            price: param.pricing,
        }, {
            only: ['providers'],
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    )
}
</script>

<template>
    <section class="banner-sec inner-banner">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                    <h1>Find service provider</h1>
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

    <section class="provider-sec">
        <div class="container">
            <div class="provider-row">
                <!-- Provider Filter Panel Start -->
                <ProviderFilter :services="services" :serviceIds="serviceIds" :price="price"
                    v-on:option-selected="handleFilter" />
                <!-- Provider Filter Panel End -->
                <div class="provider-right">
                    <div class="provider-head-wrap">
                        <p class="d-none">10 results are showing out of 100</p>
                        <button class="side-bar-toggler">
                            <span class="stick"></span>
                        </button>
                    </div>

                    <div class="row provider-list-row">
                        <template v-if="providers">
                            <div class="col-md-6 provider-col" v-for="provider in providers.data" :key="provider.id">
                                <div class="provider-list-card">
                                    <div class="card-head">
                                        <div class="card-profile">
                                            <div class="card-profile-img-wrap">
                                                <img src="/public/frontend_assets/images/profile-image-01.png"
                                                    alt="profile-image-01">
                                                <figure>
                                                    <img src="/public/frontend_assets/images/profile-star.svg"
                                                        alt="profile-star">
                                                </figure>
                                            </div>
                                            <div class="card-profile-details">
                                                <h3 v-text="provider.provider.name"></h3>
                                                <div class="ratings">
                                                    <figure>
                                                        <img src="/public/frontend_assets/images/profile-star.svg"
                                                            alt="profile-star">
                                                    </figure>
                                                    <p>4.9</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-tags">
                                            <span class="tag">{{ provider.service.name }}</span>
                                            <p class="price" v-text="fm.from(parseInt(provider.price/100))"></p>
                                        </div>
                                    </div>
                                    <div class="provider-card-details">
                                        <p v-text="provider.description"></p>
                                        <Link class="booknow" :href="`/provider/${provider.id}`" @click.prevent="">Book now</Link>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <template v-if="providers.data.length">
                        <Pagination :pagination="providers" />
                    </template>
                </div>
            </div>
        </div>
        <div class="sidebar-overlay"></div>
    </section>
</template>