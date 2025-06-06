<script setup>
import { Link } from '@inertiajs/vue3'
import Onboard from '../../../components/frontend/provider/Onboard.vue'

defineProps({
    currentUrl: String
})
</script>

<template>
    <Onboard currentUrl="/">
        <div class="thank-you-inner">
            <figure><img src="/public/frontend_assets/images/check.svg" alt="check"></figure>
            <h1>Thank You for the Registration</h1>
            <p>Your account is under review.</p>
        </div>
        <div class="form-input">
            <p>Back to <Link href="/login">Login</Link></p>
        </div>
    </Onboard>
</template>