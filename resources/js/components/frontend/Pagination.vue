<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    pagination: Object
})

const pageLinks = computed(() => {
    return props.pagination.links.filter(
        item => item.label != "&laquo; Previous" && item.label != "Next &raquo;"
    )
})
</script>

<template>
    <div class="cmn-pgns">
        <ul>
            <li class="prev" v-if="pagination.current_page > 1">
                <Link :href="pagination.prev_page_url">Prev</Link>
            </li>
            <template v-for="link in pageLinks" :key="link.label">
                <li>
                    <Link :class="{ 'active': link.active }" :href="link.url">{{ link.label }}</Link>
                </li>
            </template>
            <li class="next" v-if="pagination.current_page !== pagination.last_page">
                <Link :href="pagination.next_page_url">Next</Link>
            </li>
        </ul>
    </div>
</template>