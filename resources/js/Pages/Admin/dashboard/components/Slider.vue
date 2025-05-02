<template>
    <!-- <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-body">
            <Carousel :value="sliders" :numVisible="1" :numScroll="1" :circular="true" :showIndicators="false" :showNavigators="false">
                <template #item="slotProps">
                    <div class="row ">
                        <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Website Analytics</h5>
                            <small>Total 28.5% Conversion Rate</small>
                        </div>
                        <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                            <h6 class="text-white mt-0 mt-md-3 mb-3">Spending</h6>
                            <div class="row">
                            <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12h</p>
                                    <p class="mb-0">Spend</p>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">127</p>
                                    <p class="mb-0">Order</p>
                                </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">18</p>
                                    <p class="mb-0">Order Size</p>
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">2.3k</p>
                                    <p class="mb-0">Items</p>
                                </li>
                                </ul>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                            <img src="/public/admin_assets/assets/img/illustrations/card-website-analytics-2.png"
                            alt="Website Analytics"
                            width="170"
                            class="card-website-analytics-img" />
                        </div>
                    </div>
                </template>
            </Carousel>
        </div>
      </div>
    </div> -->
</template>
<script setup>
    import Carousel from 'primevue/carousel';
    import {ref} from 'vue';

    const sliders = ref([{ name: 'Dashboard' }, { name: 'Dashboard' }]);

</script>
