<script setup>
import { ref, onMounted, onUnmounted, defineProps, nextTick } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import DigitalSambaEmbedded from '@digitalsamba/embedded-sdk'
import { Modal } from 'bootstrap'
import dayjs from 'dayjs'

const props = defineProps({
    meetingUrl: {
        type: String,
        required: true
    },
    token: {
        type: String,
        required: true
    },
    isLoggedIn: {
        type: Boolean,
        required: true
    },
    role: {
        type: String,
        required: true
    },
    booking: {
        type: Object,
        required: true
    }
})

const meeting = ref(null)
const alertModal = ref(null)
const timer = ref('')
let timerInterval = null
let modalInstance = null
const endTime = ref(null)
const meetingNotStarted = ref(false)
let sambaFrame = null
const modalShown = ref(false);
const modalMessage = ref('');

function showModalOnce() {
    if (modalShown.value) return;
    modalShown.value = true;

    if (props.role === 'USER') {
        modalMessage.value = 'The other user has left the meeting. You will now be redirected.';
    } else {
        modalMessage.value = 'The meeting has ended.';
    }
    
    if (modalInstance) {
        modalInstance.show();
    }
}

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const testMode = urlParams.get('test_mode');
    const now = dayjs();
    const startTime = dayjs(props.booking.start_date);

    if (testMode === 'true') {
        endTime.value = dayjs().add(5, 'second');
    } else {
        endTime.value = dayjs(props.booking.end_date);
    }

    if (now.isBefore(startTime)) {
        meetingNotStarted.value = true;
    } else {
        loadMeeting();
    }

    modalInstance = new Modal(alertModal.value)

    alertModal.value.addEventListener('hidden.bs.modal', event => {
        // This event is fired when the modal has finished being hidden from the user.
        router.visit(`/`, { replace: true })
    });

    timerInterval = setInterval(updateTimer, 1000)
})

onUnmounted(() => {
    clearInterval(timerInterval)
})

function loadMeeting() {
    sambaFrame = DigitalSambaEmbedded.createControl({
        url: props.meetingUrl,
        token: props.token,
        root: meeting.value,
    });

    sambaFrame.load();

    sambaFrame.on('sessionEnded', () => {
        showModalOnce();
    });

    sambaFrame.on('userLeft', (event) => {
        // For a 1-on-1 call, if one user leaves, we end the session for the other.
        timer.value = 'Meeting has ended.'
        clearInterval(timerInterval);
        sambaFrame.endSession(false);
        showModalOnce();
    });
}

function updateTimer() {
    const now = dayjs()
    const startTime = dayjs(props.booking.start_date);

    if (now.isAfter(startTime) && meetingNotStarted.value) {
        meetingNotStarted.value = false;
        nextTick(() => {
            loadMeeting();
        });
    }

    if (now.isAfter(endTime.value)) {
        timer.value = 'Meeting has ended.'
        clearInterval(timerInterval)
        if (sambaFrame) {
            sambaFrame.endSession(false);
        }
        showModalOnce();
        return
    }

    if(!meetingNotStarted.value) {
        const timeRemainingInSeconds = endTime.value.diff(now, 'second');

        const hours = Math.floor(timeRemainingInSeconds / 3600);
        const minutes = Math.floor((timeRemainingInSeconds % 3600) / 60);
        const seconds = timeRemainingInSeconds % 60;

        timer.value = `Time Remaining: ${hours}h ${minutes}m ${seconds}s`;
    } else {
        const timeRemainingInSeconds = startTime.diff(now, 'second');

        const hours = Math.floor(timeRemainingInSeconds / 3600);
        const minutes = Math.floor((timeRemainingInSeconds % 3600) / 60);
        const seconds = timeRemainingInSeconds % 60;

        timer.value = `Meeting starts in: ${hours}h ${minutes}m ${seconds}s`;
    }
}
</script>

<template>
    <div class="dashboard-sec meeting bookings h-screen">
        <div class="dashboard-container h-full">
            <div class="dashboard-head">
                <div class="dashboard-head-inner">
                    <Link href="/" class="dashboard-logo">
                        <img src="/public/frontend_assets/images/dashboard-logo.png" alt="dashboard-logo">
                    </Link>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h1>Video Meeting</h1>
                        <div class="text-muted">{{ timer }}</div>
                    </div>
                </div>
            </div>
            <div class="dashboard-inner-wrap h-full">
                <div class="dashboard-right-panel h-full">
                    <div class="dashboard-right-inner h-full">
                        <div class="bookings-sec h-full">
                            <div class="booking-content p-3 h-full">
                                <div v-if="meetingNotStarted" class="d-flex justify-content-center align-items-center h-100">
                                    <h2 class="text-muted">The meeting has not started yet.</h2>
                                </div>
                                <div v-if="!meetingNotStarted" class="video-meeting-innr h-100" ref="meeting"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="sidebar-overlay"></div>
            </div>
        </div>


        <div class="top-left-shape">
            <img src="/public/frontend_assets/images/top-left-image.png" alt="">
        </div>
        <div class="top-right-shape">
            <img src="/public/frontend_assets/images/top-right-image.png" alt="">
        </div>
        <div class="bottom-left-shape">
            <img src="/public/frontend_assets/images/bottom-left-shap.png" alt="">
        </div>
        <div class="bottom-right-shape">
            <img src="/public/frontend_assets/images/bottom-image.png" alt="">
        </div>
        <div class="top-center">
            <img src="/public/frontend_assets/images/center-image.png" alt="">
        </div>

        <!-- Modal -->
        <div class="modal fade" ref="alertModal" tabindex="-1" aria-labelledby="meetingEndedModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="meetingEndedModalLabel">Meeting Ended</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ modalMessage }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>