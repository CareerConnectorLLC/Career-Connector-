import axios from "axios";
import { defineStore } from "pinia";
import { router } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

const FormModel = {
    errors: {},
    title: '',
    text_content: '',
    blog_category_id: '',
    user_id: 0,
    image: '',
    delete_photo: false,
};

export const useBlogStore = defineStore('blogStore', {
    state:() => ({
        list: [],
        blogCategories: [],
        filterModelVisible: false,
        processing: false,
        filters: {
            global: null,
        },
        sort: {
            fieldName: null,
            shortBy: null
        },
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            from: 0,
            to: 0
        },
        form: { ...FormModel },
        imageFile: null,
        blog: null,
        toast: useToast(),
    }),

    getters: {

    },

    actions: {
        async getBlogs(page = this.pagination.current_page, perPage = this.pagination.per_page) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route('admin.blog.getBlogs'), {
                filters: this.filters,
                sorts: this.sort,
                page: this.pagination.current_page,
                per_page: this.pagination.per_page,
            });

            if (response.data) {
                this.list = response.data.data;
                console.log(response.data);
                
                 // Paginated data
                this.pagination = {
                    current_page: response.data.current_page,
                    per_page: response.data.per_page,
                    total: response.data.total,
                    from: response.data.from,
                    to: response.data.to
                };
            }
        },

        async getBlogCategories() {
            const response = await axios.get(route('admin.getBlogCategories.list'));
            if (response.data) {
                this.blogCategories = response.data;
            }
        },

        deleteBlog(id){
            
            axios.delete(route('admin.blog.delete', id))
            .then(() => {
                console.log('Blog Deleted');
                this.toast.add({ severity: 'info', summary: 'Deleted', detail: 'The Blog has been deleted successfully', life: 3000 });
                this.getBlogs();
            })
            .catch((error)=> {
                console.log(error);
            })
        },

        createBlog() {
            this.processing = true;
            const formData = new FormData();   
            // delete this.form.errors;

            for (let key in this.form) {
                formData.append(key, this.form[key]);                
            }

            if (this.form.image) {
                formData.append('image', this.form.image);
            }

            axios.post(route('admin.blog.store'), formData)
                .then(response => {
                    console.log(response);
                    
                    router.visit(route('admin.blog.list'))
                    this.toast.add({ severity: 'success', summary: 'Success', detail: 'The Blog has been created successfully', life: 3000 });
                })
                .catch(error => {
                    if (error.status === 422) {
                        // console.log(error.response);
                        this.form.errors = error.response.data.errors;
                        // console.log(this.form.errors);
                    } else {
                        console.log(error.response);
                    }
                })
                .finally(() => {
                    this.processing = false;
                })
        },

        updateBlog(id) {
            this.processing = true;
            const formData = new FormData();   
            // delete this.form.errors;

            for (let key in this.form) {
                formData.append(key, this.form[key]);                
            }

            if (this.form.image) {
                formData.append('image', this.form.image);
            }           
            axios.post(route('admin.blog.update', id), formData)
                .then(response => {
                    router.visit(route('admin.blog.list'))
                    this.toast.add({ severity: 'info', summary: 'Updated', detail: 'The Blog has been updated successfully', life: 3000 });
                })
                .catch(error => {
                    if (error.status === 422) {
                        this.form.errors = error.response.data.errors;
                        
                    } else {
                        console.log(error.response);
                    }
                })
                .finally(() => {
                    this.processing = false;
                })
        },

        changeStatus(id) {
            axios.post(route('admin.blog.change-status', id))
                .then(response => {
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        setFormData() {
            if(this.blog)
            {   console.log(this.blog);
                this.form = {
                    title: this.blog.title || '',
                    text_content: this.blog.text_content || '',
                    blog_category_id: this.blog.blog_category_id || '',
                    user_id: this.blog.user_id || 0,
                    delete_photo: false,
                }
            }
            else{
                this.form = {...FormModel };
            }
        },

        onSearchQuery() {
            if (this.filters.global.length > 2 || this.filters.global.length === 0) {
                this.getBlogs(this.pagination.current_page);
            }
        },
        onPage(event) {
            this.getBlogs(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getBlogs();
        },
    }
})