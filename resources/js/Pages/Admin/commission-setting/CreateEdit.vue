<script setup>
import { onMounted, nextTick, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const page = usePage()

const form = useForm({
    id: page.props.settings?.id ?? null,
    booking_charge: page.props.settings?.booking_charge ?? null,
    service_charge: page.props.settings?.service_charge ?? null,
})

onMounted(() => {
    nextTick(() => {
        const pageName = "Manage Platform Commission";
        const breadcrumbs = [
            {
                title: 'Manage Platform Commission'
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
    })  
});

function handleSubmit() {
    form.post(`/admin/commission-setting`);
}
</script>

<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0" v-text="`Commission Settings`"></h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="handleSubmit()">
                <div class="row g-3">
                    <div class="mb-3">
                        <label for="booking_charge">Customer Booking Fee</label>
                        <input type="input" v-model="form.booking_charge" id="booking_charge" class="form-control border-gray-200" />
                        <span class="text-danger" v-if="form.errors.booking_charge">{{ form.errors.booking_charge }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="service_charge">Commission to Provider (%)</label>
                        <input type="input" v-model="form.service_charge" id="service_charge" class="form-control border-gray-200" />
                        <span class="text-danger" v-if="form.errors.service_charge">{{ form.errors.service_charge }}</span>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2" label="Submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>