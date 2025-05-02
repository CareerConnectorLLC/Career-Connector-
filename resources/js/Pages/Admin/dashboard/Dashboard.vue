<template>
    <div>

        <Head title="Dashboard" />
        <div class="row">
            <slider />
            <Analytic>
                <div class="row">
                    <div class="col-xl-3 mb-4 col-lg-5 col-12">
                        <div class="card">
                            <div class="d-flex align-items-end row">
                                <div class="col-7">
                                    <div class="card-body text-nowrap">
                                        <h5 class="card-title mb-0">Active Clients</h5>
                                        <!-- <p class="mb-2">Best seller of the month</p> -->
                                        <h4 class="text-primary mb-1">{{ props.stats?.active_clients }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-xl-3 mb-4 col-lg-5 col-12">
                        <div class="card">
                            <div class="d-flex align-items-end row">
                                <div class="col-7">
                                    <div class="card-body text-nowrap">
                                        <h5 class="card-title mb-0">Inactive Clients</h5>
                                        <h4 class="text-primary mb-1">{{ props.stats?.inactive_clients }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-xl-3 mb-4 col-lg-5 col-12">
                        <div class="card">
                            <div class="d-flex align-items-end row">
                                <div class="col-7">
                                    <div class="card-body text-nowrap">
                                        <h5 class="card-title mb-0">Active Service Provider</h5>
                                        <!-- <p class="mb-2">Best seller of the month</p> -->
                                        <h4 class="text-primary mb-1">{{ props.stats?.active_service_provider }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-4 col-lg-5 col-12">
                        <div class="card">
                            <div class="d-flex align-items-end row">
                                <div class="col-7">
                                    <div class="card-body text-nowrap">
                                        <h5 class="card-title mb-0">Pending Service Requests</h5>
                                        <h4 class="text-primary mb-1">32</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 mb-4 col-lg-5 col-12">
                        <div class="card">
                            <div class="d-flex align-items-end row">
                                <div class="col-7">
                                    <div class="card-body text-nowrap">
                                        <h5 class="card-title mb-0">Recent Transactions</h5>
                                        <!-- <p class="mb-2">Best seller of the month</p> -->
                                        <h4 class="text-primary mb-1">56</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Activity Timeline -->
                <div class="col-xl-6 col-lg-5 col-md-5 order-0 order-md-1">
                    <div class="card mb-4">
                        <h5 class="card-header">Recent Login Attempts</h5>
                        <div class="card-body pb-0">
                            <ul class="timeline mb-0">
                                <li v-for="(activity, index) in props?.login_activity"
                                    class="timeline-item timeline-item-transparent" :class="(index == props?.login_activity.length - 1) ? 'border-0' : null">
                                    <span class="timeline-point" :class="(activity.description === 'Successful' ? 'timeline-point-success' : 'timeline-point-danger' )"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header mb-1">
                                            <h6 class="mb-0">{{(activity.description === 'Successful') ? 'Successfull Login' : 'Failed Login attempt' }}</h6>
                                            <small class="text-muted">{{dayjs(activity.created_at).format('MMM D, YYYY h:mm A')}}</small>
                                        </div>
                                        <span class="mt-2 mb-1 small text-uppercase text-muted">Details</span>
                                            <div class="info-container">
                                                <ul class="list-unstyled">
                                                    <li v-if="activity?.causer_id" class="">
                                                        <span class="fw-semibold me-1">User ID:</span>
                                                        <span>{{ activity.causer_id }}</span>
                                                    </li>
                                                    <li v-if="!activity?.causer_id" class="">
                                                        <span class="fw-semibold me-1">Email:</span>
                                                        <span>{{ activity.properties.email }}</span>
                                                    </li>
                                                    <li class="">
                                                        <span class="fw-semibold me-1">IP Address:</span>
                                                        <span>{{ activity.properties.ip }}</span>
                                                    </li>
                                                    <li class="">
                                                        <span class="fw-semibold me-1">Browser:</span>
                                                        <span>{{ activity.properties.browser }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Activity Timeline -->
            </Analytic>


        </div>
    </div>
</template>

<script setup>
import Carousel from 'primevue/carousel';
import { onMounted, ref } from 'vue';
import Analytic from './components/Analytic.vue';
import Slider from './components/Slider.vue';
import dayjs from 'dayjs';

const props = defineProps({
    stats: Object,
    login_activity: Array
});

onMounted(() => {
    emit.emit('pageName', 'Dashboard ', []);
    
})
</script>
