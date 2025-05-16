<template>
    <footer class="footer-sec">
        <div class="container-fluid">
            <div class="foter-card">
                <div class="footer-top">
                    <div class="footer-top-inner">
                        <div class="footer-wrap">
                            <div class="footer-col">
                                <Link class="footer-logo" href="/">
                                    <img src="/public/frontend_assets/images/footer-logo.png" alt="-footer-logo">
                                </Link>
                                <ul class="footer-social" v-if="settings && socialHandles.length">
                                    <li v-for="(social, index) in socialHandles" :key="index">
                                        <Link :href="social.url"
                                            target="_blank">
                                            <img :src="social.icon_url" :alt="social.platform">
                                        </Link>
                                    </li>
                                </ul>
                            </div>
                            <div class="footer-col">
                                <p class="footer-head">For Clients</p>
                                <ul>
                                    <li><a href="#url">Dui diam</a></li>
                                    <li><a href="#url">Netus</a></li>
                                    <li><a href="#url">Pellentesque</a></li>
                                    <li><a href="#url">Neque</a></li>
                                    <li><a href="#url">laoreet</a></li>
                                </ul>
                            </div>
                            <div class="footer-col">
                                <p class="footer-head">For Service Provider</p>
                                <ul>
                                    <li><a href="#url">Dui diam</a></li>
                                    <li><a href="#url">Netus</a></li>
                                    <li><a href="#url">Pellentesque</a></li>
                                    <li><a href="#url">Neque</a></li>
                                    <li><a href="#url">laoreet</a></li>
                                </ul>
                            </div>
                            <div class="footer-col">
                                <p class="footer-head">Resources</p>
                                <ul>
                                    <li><a href="#url">Dui diam</a></li>
                                    <li><a href="#url">Netus</a></li>
                                    <li><a href="#url">Pellentesque</a></li>
                                    <li><a href="#url">Neque</a></li>
                                    <li><a href="#url">laoreet</a></li>
                                </ul>
                            </div>
                            <div class="footer-col">
                                <p class="footer-head">Company</p>
                                <ul>
                                    <li><a href="#url">Dui diam</a></li>
                                    <li><a href="#url">Netus</a></li>
                                    <li><a href="#url">Pellentesque</a></li>
                                    <li><a href="#url">Neque</a></li>
                                    <li><a href="#url">laoreet</a></li>
                                    <li><a href="#url">ornare</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="copyright-cont">
                        <ul>
                            <li><a href="#url">Terms & conditions</a></li>
                            <li><a href="#url"> Privacy policy</a></li>
                            <li>
                                <p>Copyright © 2025 <a href="index.html">Career Connector Hub</a>. All rights reserved.
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>

                <img class="footer-top-shape" src="/public/frontend_assets/images/footer-top-shape.png"
                    alt="footer-top-shape">
                <img class="footer-bottom-left-shape" src="/public/frontend_assets/images/footer-bottom-left-shape.png"
                    alt="footer-bottom-left-shape">
                <img class="footer-bottom-right-shape"
                    src="/public/frontend_assets/images/footer-bottom-right-shape.png" alt="footer-bottom-right-shape">
            </div>
        </div>
    </footer>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const settings = ref(null)

onMounted(async () => {
    let response = await fetch(`/site-settings`)
    let result = await response.json()
    settings.value = result.settings
})

const socialHandles = computed(() => settings ? JSON.parse(settings.value.social_handles) : null)

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
</script>
