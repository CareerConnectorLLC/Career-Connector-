<script setup>
import { computed, onMounted } from 'vue'
import { usePage, useForm, Link } from '@inertiajs/vue3'
import Onboarding from '../../../../components/frontend/provider/Onboard.vue'

const page = usePage()

const form = useForm({
    location: {},
    description: {},
    price: {},
})

const services = computed(() => page.props.services)

const serviceDetails = computed(() => page.props.details)

onMounted(() => {
    if (serviceDetails.value) {
        services.value.forEach(service => {
            form.location[service.id] = serviceDetails.value.location[service.id]
            form.description[service.id] = serviceDetails.value.description[service.id]
            form.price[service.id] = serviceDetails.value.price[service.id]
        })
    }
})

services.value.forEach(service => {
    form.location[service.id] = '';
    form.description[service.id] = '';
    form.price[service.id] = '';
});
</script>

<template>
    <Onboarding :currentUrl="$page.props.current_url">
        <div class="login-form">
            <div class="login-head">
                <h1 v-text="`Services details`"></h1>
                <p v-text="`Lorem ipsum dolor sit amet, consectetur adipisicing.`"></p>
            </div>
            <form @submit.prevent="form.post(`/onboarding/provider-service-details`)">
                <template v-for="(service, index) in services" :key="service.id">
                    <h2>{{ service.name }}</h2>
                    <div class="form-input">
                        <label>Location</label>
                        <input class="location" v-model="form.location[service.id]" type="text" placeholder="Enter location">
                        <small class="text-danger" v-if="form.errors[`location.${service.id}`]">{{ form.errors[`location.${service.id}`] }}</small>
                    </div>

                    <div class="form-input">
                        <label>Service description</label>
                        <textarea placeholder="Write description" v-model="form.description[service.id]"></textarea>
                        <small class="text-danger" v-if="form.errors[`description.${service.id}`]">{{ form.errors[`description.${service.id}`] }}</small>
                    </div>

                    <div class="form-input">
                        <label>Price</label>
                        <input class="price" type="number" v-model="form.price[service.id]" placeholder="Write here">
                        <small class="text-danger" v-if="form.errors[`price.${service.id}`]">{{ form.errors[`price.${service.id}`] }}</small>
                    </div>
                </template>
                <div class="form-input">
                    <button type="submit">Next</button>
                </div>
            </form>
            <div class="btn-wrapper">
                <Link class="back-btn" href="/onboarding/provider-personal-info">Back</Link>
            </div>
        </div>
    </Onboarding>
</template>