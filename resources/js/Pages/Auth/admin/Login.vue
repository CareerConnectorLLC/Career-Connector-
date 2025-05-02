<template lang="">
    <Head title="Login" />
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
            <p class="mb-4">
                Please sign-in to your account and start the adventure
            </p>

            <form id="formAuthentication" class="mb-3" @submit.prevent="submit">
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
                <div class="mb-3 form-password-toggle">
                <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                    <Link :href="route('admin.forgot-password.index')">
                    <small>Forgot Password?</small>
                    </Link>
                </div>
                <div class="input-group input-group-merge">
                    <input
                    type='password'
                    id="password"
                    class="form-control"
                    v-model="form.password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password*" />

                </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" />
                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                    </div>
                </div>
                <button type="submit"  class="btn btn-primary d-grid w-100"
                :disabled="form.processing">
                <template v-if="form.processing">
                    <span class="">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </template>
                <span v-else class="indicator-label">Sign in</span>
            </button>
            </form>
        </div>
    </div>
    <!--  -->
</template>

<script setup>
import { reactive, ref, onMounted, computed, onBeforeUnmount } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

onMounted(() => {
    // Add classes to the <html> tag
    document.documentElement.classList.add("light-style", "customizer-hide");
});

// Optionally, you can remove the classes when the component is unmounted
onBeforeUnmount(() => {
    document.documentElement.classList.remove( "light-style","customizer-hide");
});

const showNewPassword = ref(false);

const form = useForm({
    email: null,
    password: null,
});

const props = defineProps({
    errors: Object,
});

function submit() {
    form.post(route("admin.login-process"));
}

</script>

<style scoped>
    @import "/public/admin_assets/vendor/css/pages/page-auth.css";
</style>
