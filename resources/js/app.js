import './bootstrap';
import { createApp, h,defineAsyncComponent } from 'vue'
import { ZiggyVue } from 'ziggy-js';
import { createPinia } from 'pinia';
import PrimeVue from 'primevue/config';
import Aura from '@primevue/themes/aura';
import ConfirmationService from 'primevue/confirmationservice';
import ToastService from 'primevue/toastservice';
import { definePreset } from '@primevue/themes'
import { createInertiaApp,Link,Head, usePage } from '@inertiajs/vue3';
import VueApexCharts from "vue3-apexcharts";
import { Icon } from "@iconify/vue";

// import AdminLayout from './Layout/Admin/Layout.vue';
// import AdminAuthLayout from './Layout/Admin/AuthLayout.vue';
// import FrontendLayout from './Layout/Frontend/Layout.vue';

const AdminLayout = defineAsyncComponent(() =>
    import("./Layout/Admin/Layout.vue")
);

const AdminAuthLayout = defineAsyncComponent(() =>
    import("./Layout/Admin/AuthLayout.vue")
);

const FrontendLayout = defineAsyncComponent(() =>
    import("./Layout/Frontend/Layout.vue")
);

const pinia = createPinia();

import emitter from 'tiny-emitter/instance';
window.emit = emitter;

const custom = definePreset(Aura, {
    semantic: {
        colorScheme: {
            light: {
                // surface: {
                //     0: 'rgba(247,247,247,1)',
                // },
                primary: {
                    50: '#e3f0fb',
                    100: '#d1e5f7',
                    500: '#174dba',
                    700: '#103a92',
                    800: '#0d2a6e',
                },
                secondary: {
                    500: '#32125E'
                }
            }
        },
    },
});


createInertiaApp({
    title: title => `${title ? title + ' - ' : ''}  ${usePage().props.appName}`,
    resolve: async name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: false })
        let page = await pages[`./Pages/${name}.vue`]()

        switch (true) {
            case name.startsWith('Admin/'):
                page.default.layout = AdminLayout;
                break;
            case name.startsWith('Auth/admin/'):
                page.default.layout = AdminAuthLayout;
                break;
            case name.startsWith('Frontend/'):
                page.default.layout = FrontendLayout;
                break;
            default:
                page;
                break;
        }
        return page
      },
      setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
          .use(plugin)
          .use(pinia)
          .use(VueApexCharts)
          .use(ToastService)
          .use(ConfirmationService)
          .use(ZiggyVue)
          .component('Link',Link)
          .component('Head',Head)
          .component("Icon", Icon)
          .use(PrimeVue,{
            theme: {
                preset: custom,
                options: {
                    darkModeSelector: 'class',
                }
            }
        })

          .mount(el)
      },
})
