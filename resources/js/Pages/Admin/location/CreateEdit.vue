<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">{{ props?.location ? 'Update' : 'Create' }} Location</h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="submit()">
                <div class="row g-3">
                    <div class="mb-3">
                        <label for="name">Location Name</label>
                        <input type="input" id="name" v-model="store.form.name" class="form-control border-gray-200"
                            placeholder="Enter Location Name Here"></input>
                        <span class="text-danger" v-if="store.form.errors?.name">{{ store.form.errors?.name[0] }}</span>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2" :loading="store.form.processing" label="Submit"/>
                        <Link :href="route('admin.location.list')" class="btn btn-outline-primary waves-effect" as="button" type="button">Cancel</Link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue'
import { usePage } from '@inertiajs/vue3'
import FilePond from '@/components/FilePond.vue'
import { useLocationStore } from '@/Stores/LocationStore';
import { Select } from 'primevue';


const page = usePage().props;
const store = useLocationStore();
const props = defineProps({
    location: Object,
});

onMounted(() => {
    nextTick(() => {
        const pageName = props?.location ? 'Update' : 'Create';
        const breadcrumbs = [
            { title: 'Location', routeName: "admin.location.list" },
            {
                title: pageName,
                routeName: props?.location ? "admin.location.list" : "admin.location.create"
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
        console.log(store);
    });
    store.location = props.location ? props.location : null;
    store.setFormData();
    
});

function submit() {
     if (props.location) {
          store.updateLocation(props.location.id);
     }
     else {
          store.createLocation();
     }
};


</script>