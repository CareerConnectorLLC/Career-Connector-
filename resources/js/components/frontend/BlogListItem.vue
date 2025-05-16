<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({
    post: Object
})
</script>

<template>
    <div class="blog-list-item">
        <div class="blog-list-card">
            <div class="blog-list-img-wrap">
                <img v-if="post.image_url" :src="post.image_url" alt="" class="blog-list-thumbnail">
                <img v-else src="/public/frontend_assets/images/blog-card-img.jpg" alt="" class="blog-list-thumbnail">
                <div class="blog-card-cta">
                    <img src="/public/frontend_assets/images/blog-card-shape.svg" alt="">
                    <Link :href="`/blog/${post.slug}`" class="blog-arw-btn">
                    <img src="/public/frontend_assets/images/right-arrow.svg" />
                    </Link>
                </div>
            </div>
            <div class="blog-list-dtls">
                <div class="blog-list-dtls-top">
                    <div class="blog-list-tags">
                        <div class="blog-tag-item">{{ post.category.name }}</div>
                    </div>
                    <div class="pst-by">Posted on <span>{{ post.created_at }}</span></div>
                </div>
                <div class="blog-list-title">
                    <Link :href="`/blog/${post.slug}`">{{ post.title }}</Link>
                </div>
                <p>
                    {{ post.teaser }}
                </p>
            </div>
        </div>
    </div>
</template>