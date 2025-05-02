<template>

    <Head title="Reset Password" />

    <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
        <div class="w-px-400 mx-auto">
            <!-- Logo -->
            <div class="app-brand mb-1">
                <a href="/" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo" style="height:100px; width:auto">
                        <img alt="Logo" src="/public/admin_assets/logo/logo.png" style="height: 100px; width: auto;"/>
                    </span>
                </a>
            </div>
            <!-- /Logo -->
            <h3 class="mb-1 fw-bold">Welcome to Admin!</h3>
            <p class="mb-4">Please enter new password to reset. If you did not make this request, you can ignore this email.</p>

            <form @submit.prevent="form.post(route('admin.forgot-password.reset-password'))">
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="text"
                        class="form-control"
                        id="password"
                        v-model="form.password"
                        placeholder="Enter your password*"/>
                        <span class="text-danger mt-3 ml-2" v-if="form.errors.password">{{ form.errors.password }}</span>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input
                        type="text"
                        class="form-control"
                        id="confirm_password"
                        v-model="form.confirm_password"
                        placeholder="Enter your confirm password*"/>
                        <span class="text-danger mt-3 ml-2" v-if="form.errors.confirm_password">{{ form.errors.confirm_password }}</span>
                </div>
                <div class="w-full">
                    <input class="btn btn-primary d-grid w-100" type="submit" value="Save">
                </div>
            </form>

        </div>
    </div>
</template>
<script setup>
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    const isPassword = ref(false);
    const form = useForm({
        password: '',
        confirm_password: '',
    });

</script>
