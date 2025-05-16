<template lang="">
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <!-- /Left Text -->
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center" >
                    <img  src="/public/admin_assets/assets/img/illustrations/Mobile encryption-cuate.svg"
                        alt="auth-login-cover"
                        class="img-fluid my-5 auth-illustration"
                        data-app-light-img="illustrations/auth-login-illustration-light.png"
                        data-app-dark-img="illustrations/auth-login-illustration-dark.png"
                    />

                    <img src="/public/admin_assets/assets/img/illustrations/bg-shape-image-light.png"
                        alt="auth-login-cover"
                        class="platform-bg"
                        data-app-light-img="illustrations/bg-shape-image-light.png"
                        data-app-dark-img="illustrations/bg-shape-image-dark.png"
                    />
                </div>
            </div>
            <!-- /Left Text -->
            <slot/>
        </div>
    </div>
</template>

<script setup>
</script>

<style scoped>
    @import "/public/admin_assets/vendor/css/pages/page-auth.css";
</style>
