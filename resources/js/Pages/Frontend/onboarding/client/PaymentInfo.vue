<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import ClientOnboardLayout from '../../../../components/frontend/customer/OnboardLayout.vue'

const form = useForm()

function handleSubmit() {
    alert(`Work is in Progress`)
}
</script>

<template>
    <ClientOnboardLayout heading="Payment information"
        caption="Lorem ipsum dolor sit amet, consectetur adipiscing elit">
        <form @submit.prevent="handleSubmit">
            <div class="form-input">
                <label>Card holder name</label>
                <input type="text" placeholder="Holder name">
            </div>

            <div class="form-input">
                <label>Card number</label>
                <input type="number" placeholder="0000 0000 0000 0000">
            </div>

            <div class="form-input">
                <label>Expiry date</label>
                <div class="form-input-inner">
                    <input type="text" placeholder="00/00" class="datepicker hasDatepicker" id="dp1747638131123">
                </div>
            </div>

            <div class="form-input">
                <div class="input-checkbox payment-info-check">
                    <label>
                        <input type="checkbox">
                        <span>Save the card for faster payment</span>
                    </label>
                </div>
            </div>

            <div class="form-input">
                <button type="submit">Finish</button>
            </div>

            <div class="form-input-btn-wrap">
                <Link href="/client-dashboard">
                    <span>Skip</span>
                    <figure>
                        <img src="/public/frontend_assets/images/skip-btn-arrow.svg" alt="skip-btn-arrow">
                    </figure>
                </Link>
            </div>
        </form>
    </ClientOnboardLayout>
</template>