<script setup>
import { ref, onMounted } from "vue";
import { Link, useForm } from '@inertiajs/vue3'
import PasswordVisibility from '../../components/frontend/PasswordVisibility.vue'

const userType = ref('USER')

const form = useForm({
    name: null,
    email: null,
    password: null,
    phone: null,
    password_confirmation: null,
    location: null,
    accept_terms: false,
    user_role: userType.value,
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const usersRole = urlParams.get('user_role');
    if (usersRole) {
        userType.value = usersRole
    }
})

const userTypeSelect = (param) => {
    userType.value = param
    form.user_role = param
}
</script>

<template>
    <section class="login">
        <div class="login-inner">
            <div class="login-left">
                <div class="login-col-innner">
                    <div class="login-log-head">
                        <Link href="/">
                            <img src="/public/frontend_assets/images/login-logo.svg" alt="logo">
                        </Link>
                    </div>
                    <div class="login-form">
                        <div class="login-head">
                            <h1>Sign up to your account</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                        </div>

                        <div class="options">
                            <ul>
                                <li v-bind:class="{ 'active': userType === 'USER' }"><a href="" @click.prevent="userTypeSelect(`USER`)">Customer</a></li>
                                <li v-bind:class="{ 'active': userType === 'SERVICE-PROVIDER' }"><a href="" @click.prevent="userTypeSelect(`SERVICE-PROVIDER`)">Service provider</a></li>
                            </ul>
                        </div>

                        <form @submit.prevent="form.post('/register')">
                            <div class="form-input">
                                <label>Name</label>
                                <input v-model="form.name" class="user" type="text" placeholder="Enter name">
                                <small class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</small>
                            </div>
                            <div class="form-input">
                                <label>Email address</label>
                                <input v-model="form.email" type="email" placeholder="Enter email address">
                                <small class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</small>
                            </div>
                            <div class="form-input">
                                <label>Phone no.</label>
                                <input v-model="form.phone" class="phone" type="tel" placeholder="Enter phone no.">
                                <small class="text-danger" v-if="form.errors.phone">{{ form.errors.phone }}</small>
                            </div>
                            <div class="form-input">
                                <label>Location</label>
                                <input v-model="form.location" class="location" type="text" placeholder="Enter location">
                                <small class="text-danger" v-if="form.errors.location">{{ form.errors.location }}</small>
                            </div>
                            <div class="form-input">
                                <label>Password</label>
                                <div class="form-input-inner">
                                    <input v-model="form.password" :type="showPassword ? `text` : `password`" placeholder="Enter password">
                                    <PasswordVisibility :showPass="showPassword" v-on:handle-password-toggle="showPassword = !showPassword" />
                                    <small class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</small>
                                </div>
                            </div>

                            <div class="form-input">
                                <label>Confirm password</label>
                                <div class="form-input-inner">
                                    <input v-model="form.password_confirmation" :type="showConfirmPassword ? `text` : `password`" placeholder="Re-enter password">
                                    <PasswordVisibility :showPass="showConfirmPassword" v-on:handle-password-toggle="showConfirmPassword = !showConfirmPassword" />
                                    <small class="text-danger" v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</small>
                                </div>
                            </div>

                            <div class="form-input">
                                <div class="input-checkbox">
                                    <label>
                                        <input v-model="form.accept_terms" type="checkbox">
                                        <span>
                                            Accept terms &amp; conditions and privacy policy of Career
                                            Connector
                                        </span>
                                    </label>
                                </div>
                                <small class="text-danger" v-if="form.errors.accept_terms">{{ form.errors.accept_terms }}</small>
                            </div>

                            <div class="form-input">
                                <button type="submit">Sign up now</button>
                            </div>

                            <div class="form-input">
                                <p>Already have an account? <Link href="/login">Login</Link></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="login-right">
                <div class="login-image-wrap">
                    <figure v-if="userType === 'USER'">
                        <img src="/public/frontend_assets/images/signup-image.png" alt="sign-up-image">
                    </figure>
                    <figure v-if="userType === 'SERVICE-PROVIDER'">
                        <img src="/public/frontend_assets/images/provider-signup-img.jpg" alt="sign-up-image">
                    </figure>
                    <img class="top-shape" src="/public/frontend_assets/images/login-image-top-shape.svg"
                        alt="login-image-top-shape">
                    <img class="bottom-shape" src="/public/frontend_assets/images/login-image-bottom-shape.svg"
                        alt="login-image-bottom-shape">

                    <div class="login-image-ccont">
                        <div class="login-image-ccont-inner">
                            <h2>Welcome to The Career Connector</h2>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce non nisl sed ipsum
                                pulvinar pellentesque ut laoreet nunc.
                            </p>
                        </div>
                    </div>
                    <div class="image-blur-shape"></div>
                </div>
            </div>
        </div>
        <div class="logn-in-ban-top-shape"></div>
        <img class="sign-up-buttom-shape" src="/public/frontend_assets/images/bottom-shape.png" alt="bottom-shape">
        <div class="bottom-blur-shape"></div>
    </section>
</template>