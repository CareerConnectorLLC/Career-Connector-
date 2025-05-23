<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">{{ props?.service ? 'Update' : 'Create' }} Service</h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="submit()">
                <div class="row g-3">
                    <div class="mb-3">
                        <label for="name">Service Name</label>
                        <input type="input" id="name" v-model="store.form.name" class="form-control border-gray-200"
                            placeholder="Enter Service Name Here"></input>
                        <span class="text-danger" v-if="store.form.errors?.name">{{ store.form.errors?.name[0] }}</span>
                    </div>                   
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="title">Service Category</label>
                            <Select v-model="store.form.category_id" :options="store.categories" filter optionLabel="name" optionValue="id" placeholder="Select a Category" class="w-100 h-10" unstyle/>
                            <span class="text-danger" v-if="store.form.errors?.category_id">{{ store.form.errors?.category_id[0] }}</span>
                        </div>
                        <div class="col-lg-6">
                            <label for="profile_photo">Service Image</label>                     
                            <FilePond v-model="store.form.image" :myFile="(store?.service?.image_url) ? (page?.appUrl + '/storage/' + store.service?.image_url) : null" @removefile="handleFileRemove"/>
                            <span class="text-danger" v-if="store.form.errors?.image">{{ store.form.errors?.image[0] }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="text_content">Service Description</label>
                        <Editor v-model="store.form.description" editorStyle="height: 120px" >
                            <template v-slot:toolbar>
                            <span class="ql-formats">
                                <button v-tooltip.bottom="'Bold'" class="ql-bold"></button>
                                <button v-tooltip.bottom="'Italic'" class="ql-italic"></button>
                                <button v-tooltip.bottom="'Underline'" class="ql-underline"></button>
                            </span>
                        </template>
                        </Editor>

                        <span class="text-danger" v-if="store.form.errors?.description">{{ store.form.errors?.description[0] }}</span>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2" :loading="store.form.processing" label="Submit"/>
                        <Link :href="route('admin.service.list')" class="btn btn-outline-primary waves-effect" as="button" type="button">Cancel</Link>
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
import { useServiceStore } from '@/Stores/ServiceStore';
import { Select } from 'primevue';


const page = usePage().props;
const store = useServiceStore();
const props = defineProps({
    service: Object,
});

onMounted(() => {
    nextTick(() => {
        const pageName = props?.service ? 'Update' : 'Create';
        const breadcrumbs = [
            { title: 'Service', routeName: "admin.service.list" },
            {
                title: pageName,
                routeName: props?.service ? "admin.service.list" : "admin.service.create"
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
    });
    store.service = props.service ? props.service : null;
    store.setFormData();
    store.getCategories();
    store.form.categories = props.categories ? props.categories : [];
    
});

function submit() {
     if (props.service) {
          store.updateService(props.service.id);
     }
     else {
          store.createService();
     }
};

const handleFileRemove = () => {
    store.form.image = null;
    console.log(store.form?.image);
};

</script>