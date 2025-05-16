<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import RelatedBlogs from '../../../components/frontend/RelatedBlogs.vue'

const page = usePage()

const post = page.props.post
const relatedPosts = page.props.related_posts
</script>

<template>
    <section class="banner-sec inner-banner blog-details-banner">
        <div class="container-fluid">
            <div class="banner-inner">
                <div class="banner-inner-cont-wrap">
                </div>
                <img class="top-shape" src="/public/frontend_assets/images/banner-top-shape.png" alt="banner-top-shape">
                <img class="bottom-shape" src="/public/frontend_assets/images/inner-banner-right-shape.svg" alt="banner-bottom-shape">
                <img class="left-blur-shape" src="/public/frontend_assets/images/banner-left-blur-shape.png" alt="banner-left-blur-shape">
                <img class="right-blur-shape" src="/public/frontend_assets/images/banner-top-blur-shape.png" alt="banner-top-blur-shape">
            </div>
        </div>
    </section>
    <section class="blog-dtls-wrap">
        <img src="/public/frontend_assets/images/blog-list-right-glow.svg" alt="" class="blog-dtls-right-glow">
        <div class="container">
            <div class="blog-dtls-cont">
                <div class="blog-dtls-thumb">
                    <img v-if="post.image_url" :src="post.image_url" alt="">
                    <img v-else src="/public/frontend_assets/images/blog-dtls-pic.jpg" alt="">
                </div>
                <div class="blog-dtls-text">
                    <div class="blog-list-dtls-top">
                        <div class="blog-auth">
                            <img v-if="post.user.profile_photo_url" :src="post.user.profile_photo_url" alt="review-image">
                            <img v-else src="/public/admin_assets/assets/img/avatars/16.png" alt="review-image">
                            <span v-text="post.user.full_name"></span>
                        </div>
                        <div class="blog-list-tags">
                            <div class="blog-tag-item">{{ post.category.name }}</div>
                        </div>
                        <div class="pst-by">Posted on <span>{{ post.created_at }}</span></div>
                    </div>

                    <h1>{{ post.title }}</h1>
                    
                    <div v-html="post.text_content"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Posts Section -->
    <RelatedBlogs :posts="relatedPosts" />
</template>