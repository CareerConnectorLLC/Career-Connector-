<script setup>
import { router } from '@inertiajs/vue3'; 
defineProps({
    customer: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['proceed-to-add-card'])

function addPaymentMethod() {
    emit('proceed-to-add-card')
}
</script>

<template>
    <div class="modal fade" id="noCardModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="noCardModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="noCardModalLabel">Dear {{ customer.name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fs-4">You have not added any payment method yet. Please add a credit card to proceed with the booking.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" @click="addPaymentMethod" class="btn btn-primary">Add a credit card</button>
                </div>
            </div>
        </div>
    </div>
</template>