<script setup>
import { ref, onMounted } from "vue";
import { Link, useForm } from '@inertiajs/vue3'
import PasswordVisibility from '../../components/frontend/PasswordVisibility.vue';
import AuthBaseLayout from "../../components/frontend/auth/BaseLayout.vue";

const showPassword = ref(false)
const showConfirmPassword = ref(false)

onMounted(() => {
    let urlParams = new URLSearchParams(window.location.search),
    tokenCode = urlParams.get('token'),
    email = urlParams.get('email')

    if (tokenCode) {
        form.token = tokenCode
    }

    if (email) {
        form.email = email
    }
})

const form = useForm({
    email: null,
    password: null,
    password_confirmation: null,
    token: null
})
</script>

<template>
    <AuthBaseLayout heading="Reset password" caption="Lorem ipsum dolor sit amet, consectetur adipiscing elit">
        <form @submit.prevent="form.post(`/reset-password`)">
            <div class="form-input">
                <label>New password</label>
                <div class="form-input-inner">
                    <input v-model="form.password" :type="showPassword ? `text` : `password`" placeholder="*************">
                    <PasswordVisibility :showPass="showPassword" v-on:handle-password-toggle="showPassword = !showPassword" />
                    <small class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</small>
                </div>
            </div>
            <div class="form-input">
                <label>Confirm password</label>
                <div class="form-input-inner">
                    <input v-model="form.password_confirmation" :type="showConfirmPassword ? `text` : `password`" placeholder="*************">
                    <PasswordVisibility :showPass="showConfirmPassword" v-on:handle-password-toggle="showConfirmPassword = !showConfirmPassword" />
                    <small class="text-danger" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</small>
                </div>
            </div>
            <div class="form-input">
                <button type="submit">Submit</button>
            </div>
            <div class="form-input">
                <p>Back to <Link href="/login">Login</Link></p>
            </div>
        </form>
    </AuthBaseLayout>
</template>