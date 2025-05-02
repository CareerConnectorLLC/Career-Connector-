<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">{{ props?.blog ? 'Update' : 'Create' }} Blog</h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="submit()">
                <div class="row g-3">
                    <div class="mb-3">
                        <label for="title">Blog Title</label>
                        <textarea type="text" id="title" v-model="store.form.title" class="form-control border-gray-200"
                            placeholder="Title"></textarea>
                        <span class="text-danger" v-if="store.form.errors?.title">{{ store.form.errors?.title[0] }}</span>
                    </div>
                   
                    <div class="row">
                        <div class="col-lg-6">
                            <label for="title">Category</label>
                            <Select v-model="store.form.blog_category_id" :options="store.blogCategories" filter optionLabel="name" optionValue="id" placeholder="Select a Category" class="w-100 h-10" unstyle/>
                            <span class="text-danger" v-if="store.form.errors?.blog_category_id">{{ store.form.errors?.blog_category_id[0] }}</span>
                        </div>
                        <div class="col-lg-6">
                            <label for="profile_photo">Blog Image</label>                     
                            <FilePond v-model="store.form.image"
                                :myFile="(store?.blog?.image_url) ? (page?.appUrl + '/storage/' + store.blog?.image_url) : ''"
                                @removefile="handleFileRemove"/>
                            <span class="text-danger" v-if="store.form.errors?.image">{{ store.form.errors?.image[0] }}</span>
                        </div>                     
                    </div>
                    <div class="mb-3">
                        <label for="text_content">Blog Content</label>
                        <Editor v-model="store.form.text_content" editorStyle="height: 320px" />
                        <span class="text-danger" v-if="store.form.errors?.text_content">{{ store.form.errors?.text_content[0] }}</span>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2" :loading="store.processing" label="Submit"/>
                        <Link :href="route('admin.blog.list')" class="btn btn-outline-primary waves-effect" as="button" type="button">Cancel</Link>
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
import { useBlogStore } from '@/Stores/BlogStore';
import { Select } from 'primevue';


const page = usePage().props;
const store = useBlogStore();
const props = defineProps({
    blog: Object,
});

onMounted(() => {
    nextTick(() => {
        const pageName = "Blog Management";
        const breadcrumbs = [
            { title: 'Blogs', routeName: "admin.blog.list" },
            {
                title: props?.blog ? 'Update' : 'Create',
                routeName: props?.blog ? "admin.blog.list" : "admin.blog.create"
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
    });
    store.blog = props.blog ? props.blog : null;
    store.getBlogCategories();
    store.setFormData();
    
});

function submit() {
     if (props.blog) {
          store.updateBlog(props.blog.id);
     }
     else {
          store.createBlog();
     }
};

const handleFileRemove = () => {
    store.form.delete_photo = true
    store.form.image = null;
    console.log(store.form?.profile_photo);
};

</script>