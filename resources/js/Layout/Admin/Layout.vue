<template>
    <div>
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <Sidebar/>
                <!-- / Menu -->
    
                <!-- Layout container -->
                <div class="layout-page">
    
                    <!-- Navbar -->
                    <Header/>
                    <!-- / Navbar -->
    
                    <!-- Content wrapper -->
                    <div class="content-wrapper">
                        <div class="container-xxl flex-grow-1 container-p-y mb-5">
                            <!-- Breadcrumb Start -->
                            <Breadcrumb/>
                            <!-- Breadcrumb End -->
    
    
                            <!-- Content -->
                            <slot/>
                            <!-- / Content -->
                        </div>
                        <!-- Footer -->
                        <Footer/>
                        <!-- / Footer -->
    
                        <div class="content-backdrop fade"></div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>
            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
            <!-- Drag Target Area To SlideIn Menu On Small Screens -->
            <div class="drag-target"></div>
        </div>
    
        <div style="display: none;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" style="width: 16px;" id="svgIcon">
                <g id="view-details-svg">
                    <path d="M12.9531 11.0421L20.7804 3.21484" stroke="var(--bs-link-color)" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M21.5506 7.03494V2.45312H16.9688" stroke="var(--bs-link-color)" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M11.044 2.45312H9.13494C4.36222 2.45312 2.45312 4.36222 2.45312 9.13494V14.8622C2.45312 19.6349 4.36222 21.544 9.13494 21.544H14.8622C19.6349 21.544 21.544 19.6349 21.544 14.8622V12.9531"
                        stroke="var(--bs-link-color)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </g>
            </svg>
        </div>
    
    
        <ConfirmDialog group="headless" :style="{ width: '25rem' }" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
            <template #container="{ message, acceptCallback, rejectCallback }">
                <div class="d-flex flex-column align-items-center p-4 rounded">
                    <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                        style="height: 6rem; width: 6rem; margin-top: -3rem;">
                        <Icon icon="octicon:question-16" width="48" height="48" />
                    </div>
                    <span class="fw-bold fs-4 d-block mb-2 mt-3">{{ message.header }}</span>
                    <p class="mb-0">{{ message.message }}</p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <button class="btn btn-primary" @click="acceptCallback">Confirm</button>
                        <button class="btn btn-outline-secondary" @click="rejectCallback">Cancel</button>
                    </div>
                </div>
            </template>
        </ConfirmDialog>
        <Toast />
    </div>

</template>

<script setup>
    import Toast from 'primevue/toast';
    import { useToast } from 'primevue/usetoast';
    import { onMounted, onBeforeUnmount, ref,onUnmounted } from 'vue';
    import { usePage, router } from "@inertiajs/vue3";
    import Header from './Header.vue';
    import Sidebar from './Sidebar.vue';
    import Footer from './Footer.vue';
    import Breadcrumb from './Breadcrumb.vue';

    const toast = useToast();


    // Listen to router events
    let stopListening = router.on('finish', () => {
        const { props } = usePage();

        if (props.flash?.success) {
            showToast('success', 'Success', props.flash.success);
        }
        if (props.flash?.error) {
            showToast('error', 'Error', props.flash.error);
        }
        if (props.flash?.info) {
            showToast('info', 'Info', props.flash.info);
        }
        if (props.flash?.warning) {
            showToast('warning', 'Warning', props.flash.warning);
        }
    });



    onMounted(() => {
        document.documentElement.classList.add('light-style', 'layout-navbar-fixed', 'layout-menu-fixed', 'layout-footer-fixed');
    });

    onBeforeUnmount(() => {
        document.documentElement.classList.remove('light-style', 'layout-navbar-fixed', 'layout-menu-fixed', 'layout-footer-fixed', 'layout-menu-collapsed', 'layout-menu-hover');
    });

    onUnmounted(() => {
        stopListening();
    });

      // Function to show toast notifications
      function showToast(severity, summary, detail, life = 3000) {
        toast.add({ severity, summary, detail, life });
    }

</script>

<style>
        /* .card-header {
        margin: 0;
        padding: .75rem;
        margin-bottom: .75rem;
    } */

    .border-bottom {
        border-bottom: 1px solid #ebebeb !important;
    }

    html:not(.layout-menu-collapsed) .bg-menu-theme .menu-inner .menu-item:not(.active) .menu-link:hover,
    .layout-menu-hover.layout-menu-collapsed .bg-menu-theme .menu-inner .menu-item:not(.active) .menu-link:hover {
        background: #cccfe0 !important;
    }

    .p-datatable-header {
        --p-inputtext-padding-y: 0.4rem;
        --p-form-field-padding-x: 0.5rem;
    }



    .custom-swal-popup {
        z-index: 2000;
        /* Adjust to a value high enough to appear above other elements */
    }

    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar {
        z-index: 105;
    }

    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-menu,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-menu {
        z-index: 105;
    }

    :deep(.p-paginator-page.p-paginator-page-selected) {
        background-color: #8880df !important;
        color: #ffffff !important;
    }


/*
    @media (max-width: 1560px) {
        body {
            font-size: 96%;
        }
    }

    @media (max-width: 1500px) {
        body {
            font-size: 90%;
        }
    }

    @media (max-width: 1400px) {
        body {
            font-size: 80%;
        }
    }

    @media (max-width: 1310px) {
        body {
            font-size: 78%;
        }
    }

    @media (max-width: 1310px) {
        body {
            font-size: 70%;
        }
    }

    @media (max-width: 1200px) {
        body {
            font-size: 100%;
        }
    } */

</style>
