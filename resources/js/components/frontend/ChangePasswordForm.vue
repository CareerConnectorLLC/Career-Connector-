<script setup>
import { ref } from "vue";
import { useForm } from '@inertiajs/vue3'
import PasswordVisibility from './PasswordVisibility.vue'

const emit = defineEmits(['password-update-success'])

const form = useForm({
    old_password: null,
    password: null,
    password_confirmation: null,
})

const showOldPassword = ref(false)
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const changePassword = () => {
    form.post(`/change-password`, {
        onSuccess: () => {
            form.reset()
            emit('password-update-success')
        }
    })
}
</script>

<template>
    <form class="login-form" @submit.prevent="changePassword">
        <div class="form-input">
            <label>Old password</label>
            <div class="form-input-inner">
                <input :type="showOldPassword ? `text` : `password`" v-model="form.old_password"
                    placeholder="*************">
                <PasswordVisibility :showPass="showOldPassword"
                    v-on:handle-password-toggle="showOldPassword = !showOldPassword" />
            </div>
            <small class="text-danger" v-if="form.errors.old_password">{{ form.errors.old_password }}</small>
        </div>

        <div class="form-input">
            <label>New password</label>
            <div class="form-input-inner">
                <input :type="showPassword ? `text` : `password`" v-model="form.password" placeholder="*************">
                <PasswordVisibility :showPass="showPassword"
                    v-on:handle-password-toggle="showPassword = !showPassword" />
            </div>
            <small class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</small>
        </div>

        <div class="form-input">
            <label>Confirm password</label>
            <div class="form-input-inner">
                <input :type="showConfirmPassword ? `text` : `password`" v-model="form.password_confirmation"
                    placeholder="*************">
                <PasswordVisibility :showPass="showConfirmPassword"
                    v-on:handle-password-toggle="showConfirmPassword = !showConfirmPassword" />
            </div>
            <small class="text-danger" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</small>
        </div>
        <div class="form-input">
            <button type="submit">Save</button>
        </div>
    </form>
</template>