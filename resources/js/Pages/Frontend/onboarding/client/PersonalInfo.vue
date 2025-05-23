<script setup>
import { computed, onMounted, useTemplateRef } from "vue";
import { useForm, usePage } from '@inertiajs/vue3'
import Datepicker from 'vanillajs-datepicker/Datepicker'
import ClientOnboardLayout from '../../../../components/frontend/customer/OnboardLayout.vue'

const page = usePage()
const user = computed(() => page.props.auth.user)
const datePicker = useTemplateRef('datePicker')

const form = useForm({
    location: null,
    email: user.value.email,
    country: null,
    state: null,
    zip_code: null,
    date_of_birth: null,
    mode: 'onboarding',
})

onMounted(() => {
    const datepicker = new Datepicker(datePicker.value)

    datePicker.value.addEventListener('changeDate', (e) => {
        let date = e.detail.date
        form.date_of_birth = date.toLocaleDateString('en-US')
    })
})
</script>

<template>
    <ClientOnboardLayout heading="Personal information"
        caption="Lorem ipsum dolor sit amet, consectetur adipiscing elit">
        <form @submit.prevent="form.post(`/client-profile`)">
            <div class="form-input">
                <label>Address</label>
                <input v-model="form.location" class="location" type="text" placeholder="Enter address">
                <small class="text-danger" v-if="form.errors.location">{{ form.errors.location }}</small>
            </div>

            <div class="form-input">
                <label>Email address</label>
                <input v-model="form.email" type="email" readonly placeholder="Enter email address">
            </div>

            <div class="form-input-row row">
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>Country</label>
                        <select v-model="form.country">
                            <option value="">Select</option>
                            <option value="United States">United States</option>
                            <option value="Britain">Britain</option>
                        </select>
                        <small class="text-danger" v-if="form.errors.country">{{ form.errors.country }}</small>
                    </div>
                </div>
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>State</label>
                        <select v-model="form.state">
                            <option value="">Select</option>
                            <option value="New York">New York</option>
                            <option value="London">London</option>
                        </select>
                        <small class="text-danger" v-if="form.errors.state">{{ form.errors.state }}</small>
                    </div>
                </div>
            </div>

            <div class="form-input-row row">
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>ZIP Code</label>
                        <input v-model="form.zip_code" type="text" placeholder="Enter Zip Code">
                        <small class="text-danger" v-if="form.errors.zip_code">{{ form.errors.zip_code }}</small>
                    </div>
                </div>
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>Date of birth</label>
                        <div class="form-input-inner commonDatepickerStyle">
                            <input type="text" ref="datePicker">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-input">
                <button type="submit">Next</button>
            </div>
        </form>
    </ClientOnboardLayout>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css');
</style>