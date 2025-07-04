<script setup>
import { ref, watch, reactive, onMounted, useTemplateRef } from 'vue'
import { router } from '@inertiajs/vue3'
import dayjs from 'dayjs'
import { FormatMoney } from 'format-money-js'
import Datepicker from 'vanillajs-datepicker/Datepicker'

const props = defineProps({
    provider: Object,
    service: Object,
    timings: Object,
    fees: Number,
    modalShown: Boolean,
})

const emit = defineEmits(['booking-success'])

const timeSlots = ref(null)
const processing = ref(false)
const datePicker = useTemplateRef('datePicker')
const errorMsg = useTemplateRef('errorMsg')
const dateSelected = ref(false)
const datepicker = ref(null)

const fm = new FormatMoney({
    decimals: 0,
    symbol: '$'
});

const form = reactive({
    date: dayjs().format('YYYY-MM-DD'),
    time: null,
})

onMounted(() => {
    const date = new Date()
    
    datepicker.value = new Datepicker(datePicker.value, {
        minDate: date,
    });

    datepicker.value.setDate(new Date())

    datePicker.value.addEventListener('changeDate', (e) => {
        let { date } = e.detail
        timeSlots.value = props.timings[date.toString().split(' ')[0]]
        form.date = dayjs(date).format('YYYY-MM-DD')
    })

    timeSlots.value = props.timings[date.toString().split(' ')[0]]
    
    if (timeSlots.value && timeSlots.value.length && isTimeSlotEarlierThan(
        timeSlots.value[timeSlots.value.length - 1])) {
        let today = new Date();
        today.setDate(today.getDate() + 1)
        datepicker.value.setDate(today)
    }
})

watch(
    () => props.modalShown, (value) => {
        if (!value) {
            form.date = dayjs().format('YYYY-MM-DD')
            datepicker.value.setDate(new Date())
            
            if (!errorMsg.value.classList.contains('d-none')) {
                errorMsg.value.classList.add('d-none')
            }
            
            if (form.time) {
                form.time = null
            }

            if (dateSelected.value) {
                dateSelected.value = false
            }
        }
    }
)

function convertTimeToDateObject(timeString) {
    const match = timeString.match(/^(\d{1,2}):(\d{2})\s*(am|pm)$/i);
    
    if (!match) {
        return null
    }

    let hours = parseInt(match[1], 10);
    let minutes = parseInt(match[2], 10);
    let ampm = match[3].toLowerCase();
    
    if (ampm === 'pm' && hours < 12) {
        hours += 12;
    } else if (ampm === 'am' && hours === 12) {
        hours = 0;
    }

    const dateObject = new Date();

    dateObject.setHours(hours);
    dateObject.setMinutes(minutes);
    dateObject.setSeconds(0);
    dateObject.setMilliseconds(0);

    return dateObject
}

function isTimeSlotEarlierThan(timeString) {
    const date = new Date()
    // date.setHours(17)
    const dateObject = convertTimeToDateObject(timeString)
    return date.getTime() > dateObject.getTime()
}

function areDatesTheSameDay(date) {
    let today = new Date().getDate()
    let givenDate = new Date(date).getDate()
    return today === givenDate
}

function goToNextStep() {
    if (!form.time) {
        errorMsg.value.classList.remove('d-none')
        return
    }

    if (!errorMsg.value.classList.contains('d-none')) {
        errorMsg.value.classList.add('d-none')
    }
    
    dateSelected.value = true
}

function makeTheBooking() {
    form['provider_id'] = props.provider.id
    form['service_id'] = props.service.id
    form['amount'] = props.fees
    
    processing.value = true

    router.post(`/bookings`, form, {
        onSuccess: page => {
            emit('booking-success')
            processing.value = false
        }
    })
}
</script>

<template>
    <div class="modal fade" id="bookingModal" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        {{ dateSelected ? `Booking summary` : `Book ${provider.name}` }}
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-profile">
                        <div class="card-profile-img-wrap">
                            <img v-if="!provider.profile_photo_path"
                                src="/public/frontend_assets/images/profile-image-01.png" alt="profile-image-01">
                            <img v-else :src="provider.profile_photo_url" :alt="provider.name">
                        </div>
                        <div class="card-profile-details">
                            <h2 style="font-size: 20px;">{{ provider.name }}</h2>
                        </div>
                    </div>
                    <div v-show="!dateSelected">
                        <div class="select-option-cont mt-4">
                            <h4>Select day</h4>
                            <div class="d-flex justify-content-center">
                                <div class="p-3 border" ref="datePicker"></div>
                            </div>
                        </div>

                        <div class="select-option-cont">
                            <h4>Select time</h4>
                            <div class="select-card">
                                <form v-if="timeSlots && timeSlots.length">
                                    <div class="time-list">
                                        <template v-if="areDatesTheSameDay(form.date)">
                                            <label v-for="(slot, index) in timeSlots" :key="index" v-show="!isTimeSlotEarlierThan(slot)">
                                                <input type="radio" name="time" :value="slot" v-model="form.time">
                                                <span>{{ dayjs(convertTimeToDateObject(slot)).format('hh:mm A') }}</span>
                                            </label>
                                        </template>
                                        <template v-else>
                                            <label v-for="(slot, index) in timeSlots" :key="index">
                                                <input type="radio" name="time" :value="slot" v-model="form.time">
                                                <span>{{ dayjs(convertTimeToDateObject(slot)).format('hh:mm A') }}</span>
                                            </label>
                                        </template>
                                    </div>
                                </form>
                                <span v-else class="help-block fw-semibold">No Availability</span>
                            </div>
                            <p ref="errorMsg" class="error-message d-none">
                                Please select a time
                            </p>
                        </div>
                    </div>
                    <div v-show="dateSelected">
                        <div class="selected-fields mt-4">
                            <ul>
                                <li>
                                    <figure>
                                        <img src="/public/frontend_assets/images/location-green.svg" alt="location">
                                    </figure>
                                    <div class="selected-field-cont">
                                        <p class="field-head">Service</p>
                                        <p class="field-text" v-text="service.name"></p>
                                    </div>
                                </li>
                                <li>
                                    <figure>
                                        <img src="/public/frontend_assets/images/location-green.svg" alt="location">
                                    </figure>
                                    <div class="selected-field-cont">
                                        <p class="field-head">Date</p>
                                        <p class="field-text" v-text="dayjs(form.date).format('MMM D, YYYY')"></p>
                                    </div>
                                </li>
                                <li>
                                    <figure>
                                        <img src="/public/frontend_assets/images/location-green.svg" alt="location">
                                    </figure>
                                    <div class="selected-field-cont">
                                        <p class="field-head">Time</p>
                                        <p class="field-text" v-text="form.time"></p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="select-option-cont">
                            <div class="select-card">
                                <h4>Upfront payment</h4>
                                <p v-text="fm.from(parseInt(fees/2))"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" v-show="dateSelected"
                        @click="dateSelected = false">Go Back</button>
                    <button type="button" v-if="!dateSelected" class="btn btn-primary" @click="goToNextStep">Continue</button>
                    <button type="button" v-else class="btn btn-primary" :disabled="processing" @click="makeTheBooking">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css');
</style>