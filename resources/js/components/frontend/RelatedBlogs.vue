<script setup>
import { Link } from '@inertiajs/vue3'
import BlogListItem from './BlogListItem.vue'
defineProps({
    posts: Object
})
</script>

<template>
    <section class="rltd-blog-posts">
        <div class="container-fluid">
            <div class="rltd-blog-head">
                <div class="rltd-blog-head-text">
                    <h2>Related blogs</h2>
                </div>
                <div class="rltd-blog-head-btn">
                    <Link href="/blog" class="primary-btn">View all blogs</Link>
                </div>
            </div>
            <div class="blog-list-row">
                <template v-for="post in posts" :key="post.id">
                    <BlogListItem :post="post" />
                </template>
            </div>
        </div>
    </section>
</template>