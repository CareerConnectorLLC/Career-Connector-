<script setup>
import { watch, ref } from 'vue';
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    isShown: Boolean,
    accountData: Object
})

const emit = defineEmits(['bank-details-saved']);

const form = useForm({
    account_holder_name: props.user.name,
    account_number: null,
    routing_number: null,
})

const stripeError = ref(null)

watch(
    () => props.accountData, (value) => {
        if (value) {
            form.account_holder_name = props.user.name
            form.account_number = value.last4
            form.routing_number = value.routing_number
            return
        }
    }
)

watch(
    () => props.isShown, (value) => {
        if (value) {
            if (form.hasErrors) {
                form.clearErrors()
            } else if (stripeError.value) {
                stripeError.value = null
            }
            form.reset()
        }
    }
)

const saveData = () => {
    axios.post('/bank-details', form)
    .then(response => {
        emit('bank-details-saved', { isBank: true });
    })
    .catch(error => {
        stripeError.value = error.response.data.errors;
    });
};
</script>

<template>
    <form class="login-form" @submit.prevent="saveData">
        <div class="form-input">
            <label>Account holder name</label>
            <input type="text" v-model="form.account_holder_name">
            <template v-if="stripeError && stripeError.account_holder_name">
                <small class="text-danger" v-text="stripeError.account_holder_name[0]"></small>
            </template>
        </div>

        <div class="form-input">
            <label>Account number</label>
            <input type="text" v-model="form.account_number">
            <template v-if="stripeError && stripeError.account_number">
                <small class="text-danger" v-text="stripeError.account_number[0]"></small>
            </template>
        </div>

        <div class="form-input">
            <label>Routing number</label>
            <input type="text" v-model="form.routing_number">
            <template v-if="stripeError && stripeError.routing_number">
                <small class="text-danger" v-text="stripeError.routing_number[0]"></small>
            </template>
        </div>

        <div class="form-input">
            <button type="submit" :disabled="form.processing">Save</button>
        </div>
    </form>
</template>