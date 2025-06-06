<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { Alert } from 'bootstrap'

const form = useForm({
    email: null
})

const handleSubmit = () => {
    form.post(`/forgot-password`, {
        preserveScroll: true,
        onSuccess: () => form.reset()
    })
}
</script>

<template>
    <section class="login">
        <div class="login-inner">
            <div class="login-left">
                <div class="login-col-innner login-extra-space">
                    <div class="login-log-head">
                        <Link href="/">
                        <img src="/public/frontend_assets/images/login-logo.svg" alt="logo">
                        </Link>
                    </div>

                    <div class="login-form">
                        <div class="login-head">
                            <h1>Forgot password</h1>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit
                            </p>
                        </div>

                        <template v-if="$page.props.flash.success">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ $page.props.flash.success }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </template>

                        <form @submit.prevent="handleSubmit">
                            <div class="form-input">
                                <label>Enter your email address to reset your password</label>
                                <input type="email" v-model="form.email" placeholder="Enter email address">
                                <small class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</small>
                            </div>

                            <div class="form-input">
                                <button type="submit" :disabled="form.processing">Next</button>
                            </div>

                            <div class="form-input">
                                <p>Back to
                                    <Link href="/login">Login</Link>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="login-right">
                <div class="login-image-wrap">
                    <figure>
                        <img src="/public/frontend_assets/images/login-provider-img.jpg" alt="login-image">
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

        <img class="buttom-shaope" src="/public/frontend_assets/images/login-bottom-sahpe.svg" alt="bottom-shape">
    </section>
</template>