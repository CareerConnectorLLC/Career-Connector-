<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
    bankAccounts: Object,
    hasStripeId: Boolean,
})

const emit = defineEmits(['account-data'])

const removeAccount = (param) => {
    router.delete(`/bank-details/${param}`)
}

const markAsDefault = (param) => {
    router.patch(`/bank-details/${param}`)
}

const stripeSetup = () => {
    router.post(`/stripe-setup`)
}
</script>

<template>
    <div class="col-md-4 profile-col">
        <div class="my-profile-card">
            <div class="my-profile-head">
                <h2>Account details</h2>
                <template v-if="hasStripeId">
                    <a data-fancybox="" data-src="#add-account" class="primary-btn" href="">Add new</a>
                </template>
                <template v-else>
                    <a class="primary-btn" href="" @click.prevent="stripeSetup">Set up Stripe</a>
                </template>
            </div>
            
            <div class="card-list">
                <p v-if="!bankAccounts.length">Data not available</p>

                <template v-else>
                    <div class="online-card" v-for="account in bankAccounts" :key="account.id">
                        <div class="card-cont">
                            <h4>Bank Name</h4>
                            <p v-text="account.bank_name"></p>
                        </div>
                        <div class="card-cont">
                            <h4>Account No (Last 4)</h4>
                            <p v-text="account.last4"></p>
                        </div>
                        <div class="card-cont">
                            <h4>Routing Number</h4>
                            <p v-text="account.routing_number"></p>
                        </div>
                        <div class="btn-list">
                            <span v-if="account.default_for_currency" class="badge bg-info">Default</span>
                            <template v-else>
                                <a class="edit-btn" href="" @click.prevent="markAsDefault(account.id)">
                                    <img src="/public/frontend_assets/images/default-edit.svg" alt="edit">
                                </a>
                                <a class="delete-btn" href="" @click.prevent="removeAccount(account.id)">
                                    <img src="/public/frontend_assets/images/trash.svg" alt="trash">
                                </a>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>