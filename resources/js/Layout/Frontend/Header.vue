<template>
  <header class="main-head">
    <div class="container">
      <nav class="navbar navbar-expand-lg">
        <Link class="navbar-brand" href="/"><img src="/public/frontend_assets/images/Logo.png" alt="logo"></Link>
        <button class="navbar-toggler navbar-toggler-main" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
          <span class="stick"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <button class="navbar-toggler navbar-toggler-main" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="stick"></span>
          </button>
          <ul class="navbar-nav">
            <li :class="{ 'current-menu-item': $page.url === '/' }">
              <Link href="/">Home</Link>
            </li>
            <li style="display: none;">
              <a href="">About Us</a>
            </li>
            <li :class="{ 'current-menu-item': $page.url === '/provider-listing' }">
              <Link href="/provider-listing">Find Service Provider</Link>
            </li>
            <li :class="{ 'current-menu-item': $page.url.startsWith('/blog') }">
              <Link href="/blog">Blog</Link>
            </li>
            <li :class="{ 'current-menu-item': $page.url === '/contact-us' }">
              <Link href="/contact-us">Contact Us</Link>
            </li>
          </ul>
        </div>
        <div class="btn-wrap">
          <ul>
            <template v-if="!$page.props.is_auth">
              <li>
                <Link class="primary-btn" href="/login">Log In</Link>
              </li>
              <li>
                <Link class="secondary-btn" href="/register">Sign Up</Link>
              </li>
            </template>
            <template v-else>
              <li>
                <Link class="secondary-btn" :href="dashboardLink">Dashboard</Link>
              </li>
            </template>
          </ul>
        </div>
      </nav>
    </div>

    <button class="navbar-toggler" id="navoverlay" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
      aria-label="Toggle navigation">
    </button>
  </header>
</template>



<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
const props = defineProps({
  user: Object
})

const dashboardLink = computed(() => props.user && props.user.role === 'SERVICE-PROVIDER' ? `/provider-dashboard` : `/client-dashboard`)
</script>

<style scoped></style>