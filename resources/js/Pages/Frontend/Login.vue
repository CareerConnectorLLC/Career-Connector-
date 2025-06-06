<script setup>
import { ref } from "vue";
import { Alert } from "bootstrap"
import { Link, useForm } from '@inertiajs/vue3'
import AuthBaseLayout from "../../components/frontend/auth/BaseLayout.vue"
import PasswordVisibility from '../../components/frontend/PasswordVisibility.vue'

const showPassword = ref(false)

const form = useForm({
  email: null,
  password: null,
  remember: false,
})
</script>

<template>
    <AuthBaseLayout heading="Login in to your account"
        caption="Lorem ipsum dolor sit amet, consectetur adipiscing elit">

        <template v-if="$page.props.flash.error">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ $page.props.flash.error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </template>
        <template v-if="$page.props.flash.warning">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ $page.props.flash.warning }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </template>

        <template v-if="$page.props.flash.success">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $page.props.flash.success }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </template>

        <form @submit.prevent="form.post('login', { replace: true })">
            <div class="form-input">
                <label>Email address</label>
                <input type="email" v-model="form.email" placeholder="Enter email address">
                <small class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</small>
            </div>
            <div class="form-input">
                <label>Password</label>
                <div class="form-input-inner">
                    <input :type="showPassword ? `text` : `password`" v-model="form.password"
                        placeholder="Enter password">
                    <PasswordVisibility :showPass="showPassword"
                        v-on:handle-password-toggle="showPassword = !showPassword" />
                    <small class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</small>
                </div>
            </div>

            <div class="form-input">
                <div class="input-checkbox">
                    <label>
                        <input type="checkbox" v-model="form.remember">
                        <span>Remember me?</span>
                    </label>
                    <Link href="/forgot-password">Forgot password?</Link>
                </div>
            </div>

            <div class="form-input">
                <button type="submit" :disabled="form.processing">Login now</button>
            </div>

            <div class="form-input">
                <p>Don't have an account?
                    <Link href="/register">Register</Link>
                </p>
            </div>
        </form>
    </AuthBaseLayout>
</template>