import axios from "axios";
import { defineStore } from "pinia";
import { router } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

const FormModel = {
    errors: {},
    name: '',
    active: '',
};

export const useBlogCategoryStore = defineStore('blogCategoryStore', {
    state:() => ({
        list: [],
        filterModelVisible: false,
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
        blogCategory: null,
        form: { ...FormModel },
        toast: useToast(),
    }),

    getters: {

    },

    actions: {
        async getBlogCategories(page = this.pagination.current_page, perPage = this.pagination.per_page) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route('admin.blog-category.list'), {
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

        createBlogCategory() {
            axios.post(route('admin.blog-category.store'), this.form)
                .then(response => {
                    console.log(response);
                    router.visit(response.data.redirectUrl);
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
        },

        updateBlogCategory(id) {
            axios.post(route('admin.blog-category.update', id), this.form)
                .then(response => {
                    console.log(response);
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    if (error.status === 422) {
                        this.form.errors = error.response.data.errors;
                    } else {
                        console.log(error.response);
                    }
                })
        },

        deleteBlog(id){
            
            axios.delete(route('admin.blog-category.delete', id))
            .then(response => {
                router.visit(response.data.redirectUrl);
            })
            .catch((error)=> {
                console.log(error);
            })
        },

        changeStatus(id) {
            axios.post(route('admin.blog-category.change-status', id))
                .then(response => {
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        setFormData() {
            if(this.blogCategory)
            {            
                this.form = {
                    name: this.blogCategory.name || '',
                    active: this.blogCategory.active ?? '',
                }
            }
            else{
                this.form = {...FormModel };
            }
        },

        onSearchQuery() {
            if (this.filters.global.length > 2 || this.filters.global.length === 0) {
                this.getBlogCategories(this.pagination.current_page);
            }
        },
        onPage(event) {
            this.getBlogCategories(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getBlogCategories();
        },
    }
})