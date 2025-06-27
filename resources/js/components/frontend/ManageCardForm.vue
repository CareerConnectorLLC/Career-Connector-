<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    user: Object,
    id: String,
    cardError: { type: Object, default: null }
})

const form = useForm({
    name: props.user.name
})
</script>

<template>
    <form class="login-form" id="card-form">
        <div class="form-input">
            <label>Name on Card</label>
            <div class="form-input-inner">
                <input type="text" v-model="form.name">
            </div>
        </div>

        <div class="form-input">
            <label>Card details</label>
            <div class="form-input-inner">
                <div :id="id" class="p-3 border rounded-2 bg-body"></div>
                <small class="text-danger" v-if="cardError" v-text="cardError.message"></small>
            </div>
        </div>

        <div class="form-input">
            <button type="submit" :disabled="form.processing">Save</button>
        </div>
    </form>
</template>