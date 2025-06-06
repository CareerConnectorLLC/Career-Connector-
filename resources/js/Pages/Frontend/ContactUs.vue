<script setup>
import { computed } from 'vue'
import { Link, useForm, usePage } from '@inertiajs/vue3'
import { Alert } from 'bootstrap'

const page = usePage()

const settings = page.props.site_settings

const socialHandles = computed(() => JSON.parse(settings.social_handles))

const form = useForm({
    full_name: null,
    email: null,
    message: null
})

const handleSubmit = () => {
    form.post(`/contact-us`, {
        preserveScroll: true,
        onSuccess: () => form.reset()
    })
}

</script>

<template>
    <section class="banner-sec inner-banner">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                    <h1>Contact with us</h1>
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

    <section class="contact-info-sec">
        <div class="container">
            <div class="contact-info-outer">
                <div class="row contact-row">
                    <div class="col-lg-6 contct-col-left">
                        <div class="contact-info">
                            <div class="contact-sec-head">
                                <h2>Get in touch</h2>
                                <p>
                                    Pellentesque interdum felis quis dui euismod, dignissim fermentum elit elementum.
                                    Mauris malesuada eu mauris vel feugiat. Mauris vel fermentum purus
                                </p>
                            </div>
                            
                            <div class="contact-info-inner">
                                <ul>
                                    <li>
                                        <figure>
                                            <img src="/public/frontend_assets/images/location-green.svg" alt="location">
                                        </figure>
                                        <div class="info-details">
                                            <h3>Address</h3>
                                            <p v-text="settings?.address ?? ''"></p>
                                        </div>
                                    </li>
                                    <li>
                                        <figure>
                                            <img src="/public/frontend_assets/images/phone.svg" alt="phone">
                                        </figure>
                                        <div class="info-details">
                                            <h3>Phone</h3>
                                            <a href="" v-text="settings?.phone ?? ''"></a>
                                        </div>
                                    </li>
                                    <li>
                                        <figure>
                                            <img src="/public/frontend_assets/images/mail.svg" alt="mail">
                                        </figure>
                                        <div class="info-details">
                                            <h3>Email</h3>
                                            <a href="" v-text="settings?.email ?? ''"></a>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="contact-social" v-if="settings && socialHandles.length">
                                <h4>Follow us on:</h4>
                                <ul class="footer-social">
                                    <li v-for="(social, index) in socialHandles" :key="index">
                                        <a :href="social.url" target="_blank">
                                            <img :src="social.icon_url" :alt="social.platform">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 contct-col-right">
                        <div class="contact-form-card">
                            
                            <!-- Session flash message section start -->
                            <template v-if="$page.props.flash.success">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ $page.props.flash.success }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </template>
                            <!-- Session flash message section end -->

                            <h4 class="h2-title">Send us a message</h4>
                            <div class="conatact-form-inner">
                                <form @submit.prevent="handleSubmit" ref="contactForm">
                                    <div class="row contact-card-row">
                                        <div class="col-md-6 contact-card-col">
                                            <label>Your name</label>
                                            <input v-model="form.full_name" type="text" placeholder="Enter full name">
                                            <small v-if="form.errors.full_name" class="text-danger">
                                                {{ form.errors.full_name }}
                                            </small>
                                        </div>
                                        <div class="col-md-6 contact-card-col">
                                            <label>Email address</label>
                                            <input v-model="form.email" type="email" placeholder="Enter email address">
                                            <small v-if="form.errors.email" class="text-danger">
                                                {{ form.errors.email }}
                                            </small>
                                        </div>
                                        <div class="col-md-12 contact-card-col">
                                            <label>Message</label>
                                            <textarea v-model="form.message"
                                                placeholder="Write here something..."></textarea>
                                            <small v-if="form.errors.message" class="text-danger">
                                                {{ form.errors.message }}
                                            </small>
                                        </div>
                                        <div class="col-md-12 contact-card-col">
                                            <input type="submit" value="Submit">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="blur bottom-left"></div>
    </section>
</template>