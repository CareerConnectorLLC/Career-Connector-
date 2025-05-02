<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">{{ props?.blog_category ? 'Update' : 'Create' }} Blog Category</h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="submit()">
                <div class="row g-3">
                    <div class="w-50 mb-3">
                        <label for="name">Category Name</label>
                        <input type="input" id="name" v-model="store.form.name" class="form-control border-gray-200"
                            placeholder="Enter Category Name Here"></input>
                        <span class="text-danger" v-if="store.form.errors?.name">{{ store.form.errors?.name[0] }}</span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="w-50 mb-3">
                        <label class="form-label" for="active">Status</label>
                    <select class="form-select" id="active" v-model="store.form.active">
                        <option selected="" value="">Select Status</option>
                        <option :value="1">Active</option>
                        <option :value="0">Inactive</option>
                    </select>
                    <span class="text-danger" v-if="store.form.errors?.active">{{ store.form.errors?.active[0] }}</span>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2"
                            :loading="store.form.processing" label="Submit" />
                        <Link :href="route('admin.blog-category.list')" class="btn btn-outline-primary waves-effect" as="button"
                            type="button">Cancel</Link>
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
import { useBlogCategoryStore } from '@/Stores/BlogCategoryStore';
import { MultiSelect } from 'primevue';


const page = usePage().props;
const store = useBlogCategoryStore();
const props = defineProps({
    blog_category: Object,
});

onMounted(() => {
    nextTick(() => {
        const pageName = 'Blog Category Management';
        const breadcrumbs = [
            { title: 'Blog Cetegories', routeName: "admin.blog-category.list" },
            {
                title: props?.blog_category ? 'Update' : 'Create',
                routeName: ""
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
    });
    store.blogCategory = props.blog_category ? props.blog_category : null;
    store.setFormData();
    console.log(props.blog_category);


});

function submit() {
    if (props.blog_category) {
        store.updateBlogCategory(props.blog_category.id);
    }
    else {
        store.createBlogCategory();
    }
};

</script>