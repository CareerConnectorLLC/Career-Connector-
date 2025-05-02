import axios from "axios";
import { defineStore } from "pinia";
import { router } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

const FormModel = {
    erros: {},
    question: '',
    answer: ''
};

export const useFaqStore = defineStore('faqStore', {
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
        form: { ...FormModel },
        faq: null,
        toast: useToast(),
    }),

    getters: {

    },

    actions: {
        async getFaqs(page = this.pagination.current_page, perPage = this.pagination.per_page) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route('admin.faq.getFaqs'), {
                filters: this.filters,
                sorts: this.sort,
                page: this.pagination.current_page,
                per_page: this.pagination.per_page,
            });

            if (response.data) {
                this.list = response.data.data;
                // console.log(response.data);
                
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

        deleteFaq(id){
            axios.delete(route('admin.faq.delete', id))
            .then(() => {
                console.log('Deleted');
                this.getFaqs();
            })
            .catch((error)=> {
                console.log(error);
            })
        },

        createFaq() {
            axios.post(route('admin.faq.store'), this.form)
                .then(response => {
                    router.visit(route('admin.faq.list'))
                    this.toast.add({ severity: 'info', summary: 'Updated', detail: 'The FAQ has been created successfully', life: 3000 });
                })
                .catch(error => {
                    if (error.status === 422) {
                        this.form.errors = error.response.data.errors;
                    } else {
                        console.log(error.response);
                    }
                }).finally(() => {
                    this.form = { ...FormModel };
                });
        },

        updateFaq(id) {
            axios.patch(route('admin.faq.update', id), this.form)
                .then(response => {
                    router.visit(route('admin.faq.list'))
                    this.toast.add({ severity: 'info', summary: 'Updated', detail: 'The FAQ has been updated successfully', life: 3000 });
                })
                .catch(error => {
                    if (error.status === 422) {
                        this.form.errors = error.response.data.errors;
                    } else {
                        console.log(error.response);
                    }
                });
        },

        setFormData() {
            if(this.faq)
            {
                this.form = {
                    question: this.faq.question || '',
                    answer: this.faq.answer || '',
                }
            }
            else{
                this.form ={...FormModel };
            }
        },

        onSearchQuery() {
            if (this.filters.global.length > 2 || this.filters.global.length === 0) {
                this.getFaqs(this.pagination.current_page);
            }
        },
        onPage(event) {
            this.getFaqs(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getFaqs();
        },
    }
})