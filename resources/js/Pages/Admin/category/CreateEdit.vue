<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">{{ props?.category ? 'Update' : 'Create' }} Category</h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="submit()">
                <div class="row g-3">
                    <div class="mb-3">
                        <label for="name">Category Name</label>
                        <input type="input" id="name" v-model="store.form.name" class="form-control border-gray-200"
                            placeholder="Enter Category Name Here"></input>
                        <span class="text-danger" v-if="store.form.errors?.name">{{ store.form.errors?.name[0] }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="name">Description</label>
                        <input type="input" id="name" v-model="store.form.description" class="form-control border-gray-200"
                            placeholder="Enter Category Name Here"></input>
                        <span class="text-danger" v-if="store.form.errors?.description">{{ store.form.errors?.description[0] }}</span>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="form-label" for="active">Status</label>
                            <select class="form-select" id="active" v-model="store.form.active">
                                <option selected="" value="">Select Status</option>
                                <option :value="1">Active</option>
                                <option :value="0">Inactive</option>
                            </select>
                            <span class="text-danger" v-if="store.form.errors?.active">{{ store.form.errors?.active[0] }}</span>
                        </div>
                        <div class="col-lg-6">
                            <label for="profile_photo">Category Image</label>
                            <FilePond 
                                v-model="store.form.image"
                                :myFile="(store?.category?.image_url) ? (page?.appUrl + '/storage/' + store.category?.image_url) : null"
                                @removefile="handleFileRemove" />
                            <span class="text-danger" v-if="store.form.errors?.image">{{ store.form.errors?.image[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2"
                            :loading="store.form.processing" label="Submit" />
                        <Link :href="route('admin.category.list')" class="btn btn-outline-primary waves-effect"
                            as="button" type="button">Cancel</Link>
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
import { useCategoryStore } from '@/Stores/CategoryStore';
import { Select } from 'primevue';


const page = usePage().props;
const store = useCategoryStore();
const props = defineProps({
    category: Object,
});

onMounted(() => {
    nextTick(() => {
        const pageName = props?.category ? 'Update' : 'Create';
        const breadcrumbs = [
            { title: 'Category', routeName: "admin.category.list" },
            {
                title: pageName,
                routeName: props?.category ? "admin.category.list" : "admin.category.create"
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
    });
    store.category = props.category ? props.category : null;
    store.setFormData();
    
});

function submit() {
     if (props.category) {
          store.updateCategory(props.category.id);
     }
     else {
          store.createCategory();
     }
};

const handleFileRemove = () => {
    store.form.image = null;
    console.log(store.form?.image);
};

</script>