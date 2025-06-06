<script setup>
import { computed, onMounted, onBeforeMount, ref } from 'vue'
import { usePage, useForm, Link } from '@inertiajs/vue3'
import Onboarding from '../../../../components/frontend/provider/Onboard.vue'

const form = useForm({ timings: null })
const page = usePage()
const selectedDay = ref('Mon')
const dailyAvailability = computed(() => page.props.availability)
const days = computed(() => Object.keys(dailyAvailability.value).map(item => item))
const timings = ref([])

onBeforeMount(() => {
    let availableTimes = {}

    Object.keys(dailyAvailability.value).forEach(item => {
        availableTimes[item] = []
    });

    form.timings = availableTimes
})

onMounted(() => {
    timings.value = dailyAvailability.value[selectedDay.value]
})

const selectDay = (param) => {
    selectedDay.value = param
    timings.value = dailyAvailability.value[param]
}
</script>

<template>
    <Onboarding :currentUrl="page.props.current_url">
        <div class="login-form">
            <div class="login-head">
                <h1 v-text="`Set availability`"></h1>
                <p v-text="`Lorem ipsum dolor, sit amet consectetur adipisicing.`"></p>
            </div>
            <form @submit.prevent="form.post(`/onboard/provider/availability`, { preserveState: true, replace: true })">
                <h2>Select your available dates &amp; time</h2>
                <div class="form-input">
                    <label>Select day</label>
                    <div class="radio-group">
                        <template v-for="day in days" :key="day">
                            <label>
                                <input
                                    type="radio"
                                    v-bind:value="day"
                                    v-bind:checked="selectedDay === day"
                                    v-on:change="selectDay($event.target.value)"
                                    name="day"
                                >
                                <span>{{ day }}</span>
                            </label>
                        </template>
                    </div>
                </div>

                <div class="form-input">
                    <label>Select time</label>

                    <div class="checkbox-group">
                        <template v-for="(time, index) in timings" :key="index">
                            <label>
                                <input
                                    type="checkbox"
                                    v-bind:value="time"
                                    v-model="form.timings[selectedDay]"
                                >
                                <span>{{ time }}</span>
                            </label>
                        </template>
                    </div>
                    <span class="text-danger" v-if="form.errors.timings">
                        {{ form.errors.timings }}
                    </span>
                </div>
                <div class="form-input">
                    <button type="submit" :disabled="form.processing">Finish</button>
                </div>
            </form>
            <div class="btn-wrapper">
                <Link class="back-btn" href="/onboard/provider/document-upload">Back</Link>
            </div>
        </div>
    </Onboarding>
</template>