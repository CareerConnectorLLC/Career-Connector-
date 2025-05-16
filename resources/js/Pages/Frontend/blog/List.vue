<script setup>
import { Link } from '@inertiajs/vue3'
import BlogListItem from '../../../components/frontend/BlogListItem.vue'
import Pagination from '../../../components/frontend/Pagination.vue'
defineProps({
    posts: Object
})

</script>

<template>
    <section class="banner-sec inner-banner">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                    <h1>Our blogs</h1>
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
                <img class="bottom-shape" src="/public/frontend_assets/images/inner-banner-right-shape.svg" alt="banner-bottom-shape">
                <img class="left-blur-shape" src="/public/frontend_assets/images/banner-left-blur-shape.png" alt="banner-left-blur-shape">
                <img class="right-blur-shape" src="/public/frontend_assets/images/banner-top-blur-shape.png" alt="banner-top-blur-shape">
            </div>
        </div>
    </section>
    
    <div class="blog-list-sec">
        <img src="/public/frontend_assets/images/blog-list-left-glow.svg" alt="" class="blog-list-left-glow">
        <img src="/public/frontend_assets/images/blog-list-right-glow.svg" alt="" class="blog-list-right-glow">
        <div class="container">
            <div class="blog-list-row">
                <template v-if="posts.data.length">
                    <template v-for="post in posts.data" :key="post.id">
                        <BlogListItem :post="post" />
                    </template>
                </template>
            </div>
            <template v-if="posts.data.length">
                <Pagination :pagination="posts" />
            </template>
        </div>
    </div>
</template>