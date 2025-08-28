<script setup>
import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    serviceCategories: Object
})

const form = useForm({
    services: null,
    price: 0
})

onMounted(() => {
    form.services = [props.serviceCategories[0].id]
})

const serviceSelected = event => {
    form.services = [event.target.value]
}

const handleSearch = () => {
    form.get(`/provider-listing`)
}
</script>

<template>
    <form @submit.prevent="handleSearch">
        <div class="form-wrapper">
            <div class="search-col">
                <select v-on:change="serviceSelected($event)">
                    <option v-for="item in serviceCategories" :key="item.id" :value="item.id">{{ item.name }}</option>
                </select>
            </div>
            <div class="search-col">
                <input type="text" placeholder="Location" disabled>
            </div>
            <div class="search-col">
                <select v-model="form.price">
                    <option value="0">Select Price</option>
                    <option v-for="i in 10" :key="i" :value="i * 1000">${{ i * 10 }}</option>
                </select>
            </div>
            <div class="search-col">
                <input type="submit" value="search">
            </div>
        </div>
    </form>
</template>