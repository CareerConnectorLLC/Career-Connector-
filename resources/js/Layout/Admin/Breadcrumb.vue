<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <Link :href="route('admin.dashboard')">Dashboard</Link>
            </li>
            <template v-if="pageNames.length" v-for="(page, key) in pageNames" :key="key">
                <template v-if="page.routeName && page.routeName != ''">
                    <li class="breadcrumb-item">
                        <Link :href="route(page.routeName)">
                        <span class="fw-light"
                            :class="((pageNames.length) == key + 1) ? '' : 'text-muted'">{{
                                page.title
                            }}</span>
                        </Link>
                    </li>
                </template>
                <template v-else>
                    <li class="breadcrumb-item active">{{ page.title }}</li>
                </template>
            </template>
        </ol>
    </nav>

</template>

<script setup>
    import { onMounted, onBeforeUnmount, ref } from 'vue';
    import { usePage, router } from "@inertiajs/vue3";


    const pageNames = ref("");
    const title = ref("");

    onMounted(() => {
            emit.on("pageName", function (arg1, arg2) {
                pageNames.value = arg2;
            });
            emit.on("pageName", function (arg1) {
                title.value = arg1;
            });
        });

</script>
