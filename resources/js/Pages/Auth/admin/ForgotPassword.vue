<template>

    <Head title="Forgot Password" />

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
            <p class="mb-4">Forgot your password? Enter your email to reset it. If you did not make this request, you can ignore this email.</p>
            <form @submit.prevent="form.post(route('admin.forgot-password.index'))">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="text"
                        class="form-control"
                        id="email"
                        v-model="form.email"
                        placeholder="Enter your email*"/>
                        <span class="text-danger mt-3 ml-2" v-if="form.errors.email">{{ form.errors.email }}</span>
                    </div>

                    <button type="submit"  class="btn btn-primary d-grid w-100"
                    :disabled="form.processing">
                    <template v-if="form.processing">
                        <span class="">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </template>
                    <span v-else class="indicator-label">Continue</span>
                </button>
            </form>
        </div>
    </div>

</template>

<script setup>
    import { useForm } from '@inertiajs/vue3';
    const form = useForm({
        email: '',
    });
</script>
