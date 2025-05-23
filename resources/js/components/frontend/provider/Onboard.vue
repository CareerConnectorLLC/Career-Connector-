<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    currentUrl: { type: String, required: true }
})
</script>

<template>
    <section class="login provider">
        <div class="login-inner personal-info">
            <div class="login-left">
                <div class="login-col-innner">
                    <div class="login-log-head">
                        <Link :href="currentUrl">
                            <img src="/public/frontend_assets/images/login-logo.svg" alt="logo">
                        </Link>
                    </div>
                    <slot />
                </div>
            </div>
            <div class="login-right">
                <div class="login-image-wrap">
                    <figure>
                        <img src="/public/frontend_assets/images/provider-signup-img.jpg" alt="provider-signup">
                    </figure>
                    <img class="top-shape" src="/public/frontend_assets/images/login-image-top-shape.svg"
                        alt="login-image-top-shape">
                    <img class="bottom-shape" src="/public/frontend_assets/images/login-image-bottom-shape.svg"
                        alt="login-image-bottom-shape">

                    <div class="login-image-ccont">
                        <div class="login-image-ccont-inner">
                            <h2>Welcome to The Career Connector</h2>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce non nisl sed ipsum
                                pulvinar pellentesque ut laoreet nunc.
                            </p>
                        </div>
                    </div>
                    <div class="image-blur-shape"></div>
                </div>
            </div>
        </div>
        <div class="logn-in-ban-top-shape"></div>
        <img class="buttom-shaope" src="/public/frontend_assets/images/login-bottom-sahpe.svg" alt="bottom-shape">
    </section>
</template>