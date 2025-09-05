<script setup>
import { computed } from 'vue'
import { useHead } from '@vueuse/head';
import { Link, usePage } from '@inertiajs/vue3'
import BannerSearch from '../../components/frontend/BannerSearch.vue'

const page = usePage()
const services = page.props.services

useHead({
    title: page.props.seo?.title ?? page.props.site_title,
    meta: [
        { name: 'description', content: page.props.seo?.description || "" },
        { name: 'keywords', content: page.props.seo?.keywords || "" }
    ],
    link: [
        { rel: 'canonical', href: page.props.seo?.canonical_url || page.url }
    ]
})
</script>

<template>
    <section class="banner-sec">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                    <span class="tag">Welcome to Career connector</span>
                    <h1>Stop Guessing About Your Career. Start Talking To Professionals.</h1>
                    <p>
                        Make informed career decisions with authentic insights from people doing the work you're curious
                        about. Career Connector connects you directly with experienced professionals ready to share the
                        real story.
                    </p>
                    <div class="banner-serach-wrap">
                        <BannerSearch :serviceCategories="services" />
                    </div>

                    <div class="banner-image-1">
                        <img src="/public/frontend_assets/images/banner-image01.png" alt="banner-image-1">
                    </div>

                    <div class="banner-image-2">
                        <img src="/public/frontend_assets/images/banner-image02.png" alt="banner-image-2">
                    </div>
                </div>

                <img class="top-shape" src="/public/frontend_assets/images/banner-top-shape.png" alt="banner-top-shape">
                <img class="bottom-shape" src="/public/frontend_assets/images/banner-bottom-shape.png"
                    alt="banner-bottom-shape">
                <div class="banner-image-3">
                    <img src="/public/frontend_assets/images/banner-image03.png" alt="banner-image-3">
                </div>

                <img class="left-blur-shape" src="/public/frontend_assets/images/banner-left-blur-shape.png"
                    alt="banner-left-blur-shape">
                <img class="right-blur-shape" src="/public/frontend_assets/images/banner-top-blur-shape.png"
                    alt="banner-top-blur-shape">
            </div>
        </div>
    </section>

    <section class="solution-sec">
        <div class="container">
            <div class="row solution-row">
                <div class="col-md-6 solution-col-left">
                    <div class="solution-left-cont">
                        <figure class="solution-img-1">
                            <img src="/public/frontend_assets/images/solution-image01.png" alt="solution-image">
                        </figure>

                        <figure class="solution-img-2">
                            <img src="/public/frontend_assets/images/solution-image02.png" alt="solution-image">
                        </figure>
                    </div>
                </div>
                <div class="col-md-6 solution-col-right">
                    <div class="solution-cont">
                        <h2>Get Career Answers From People Who Actually Do The Work</h2>
                        <p>
                            Stop making career decisions based on job descriptions and company websites. Get the real
                            story from professionals who actually do the work you're curious about.
                        </p>
                        <p>
                            Career Connector bridges the gap between career curiosity and career clarity through
                            authentic conversations with experienced professionals. Whether you're a student choosing a
                            major, a professional considering a career change, or someone exploring advancement
                            opportunities, our platform connects you directly with people who can share what their jobs
                            are really like. From daily responsibilities and salary realities to industry challenges and
                            growth opportunities, get the unfiltered insights you need to make confident career
                            decisions. For professionals, it's an opportunity to monetize your expertise while making a
                            meaningful impact on someone's career journey. Real careers, real people, real answers.
                        </p>
                        <a class="primary-btn d-none" href="">Read more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sign-up-sec">
        <div class="container">
            <div class="sign-up-inner">
                <div class="row sign-up-row">
                    <div class="col-md-6 sign-up-col">
                        <div class="sign-up-card">
                            <div class="sign-up-card-image">
                                <img src="/public/frontend_assets/images/sign-up-image01.png" alt="sign-up-image">
                                <img class="signup-shape" src="/public/frontend_assets/images/service-image-shape.svg"
                                    alt="service-image-shape">

                                <Link class="signup-arrow" href="/register?user_role=USER">
                                <img src="/public/frontend_assets/images/right-arrow.svg" alt="right-arrow">
                                </Link>

                            </div>
                            <div class="sign-up-cont">
                                <h2>For Career Explorers</h2>
                                <p>
                                    Get authentic career insights from experienced professionals. Book conversations
                                    with people doing the work you're curious about and make informed career decisions
                                    based on real experiences, not job descriptions.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 sign-up-col">
                        <div class="sign-up-card">
                            <div class="sign-up-card-image">
                                <img src="/public/frontend_assets/images/sign-up-image02.png" alt="sign-up-image">
                                <img class="signup-shape" src="/public/frontend_assets/images/service-image-shape.svg"
                                    alt="service-image-shape">
                                <Link class="signup-arrow" href="/register?user_role=SERVICE-PROVIDER">
                                <img src="/public/frontend_assets/images/right-arrow.svg" alt="right-arrow">
                                </Link>
                            </div>
                            <div class="sign-up-cont">
                                <h2>For Professionals</h2>
                                <p>
                                    Share your career expertise and earn flexible income. Help others navigate their
                                    career journeys while building your professional brand and making a meaningful
                                    impact on someone's future.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>