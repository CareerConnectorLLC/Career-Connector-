<script setup>
import { computed, onMounted, ref, useTemplateRef } from 'vue';
import { useForm, Link, usePage } from '@inertiajs/vue3'
import ClientOnboardLayout from '../../../../components/frontend/customer/OnboardLayout.vue'

const page = usePage()

const user = computed(() => page.props.user)

const form = useForm({
    payment_method_id: null,
    stripe_id: null,
})

const clientSecret = computed(() => page.props.client_secret)

const cardForm = useTemplateRef('cardForm')

const cardError = ref(null)

const stripe = ref(null)

onMounted(() => {
    stripe.value = Stripe(page.props.stripe_key)
    const elements = stripe.value.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    cardForm.value.addEventListener('submit', async (e) => {
        e.preventDefault()
        const { setupIntent, error } = await stripe.value.confirmCardSetup(
            clientSecret.value, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: user.value.name,
                        email: user.value.email,
                    }
                }
            }
        )

        if (error) {
            cardError.value = error
        } else {
            const { payment_method } = setupIntent
            form.payment_method_id = payment_method
            form.stripe_id = user.value.stripe_id
            form.post(`/onboard/client/payment-info`, { replace: true })
        }
    })
})
</script>

<template>
    <ClientOnboardLayout heading="Payment information"
        caption="Lorem ipsum dolor sit amet, consectetur adipiscing elit">
        <form ref="cardForm">
            <div class="form-input">
                <label>Card holder name</label>
                <input type="text" v-bind:value="user.name" placeholder="Holder name">
            </div>

            <div class="form-input">
                <label>Card number</label>
                <div id="card-element" class="p-3 border rounded-2 bg-body"></div>
                <small class="text-danger" v-if="cardError" v-text="cardError.message"></small>
            </div>

            <div class="form-input">
                <button type="submit" :disabled="form.processing">Finish</button>
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