import axios from "axios";
import { defineStore } from "pinia";
import { router } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";

const FormModel = {
    errors: {},
    reply: '',
    Inquiry_id: '',
};

export const useInquiryStore = defineStore('inquiryStore', {
    state:() => ({
        list: [],
        inquiries: [],
        filterModelVisible: false,
        filters:[ 
            {
              column: "",
              value: "",
            }],
        searchFilters:
        {
            column: "",
            value: [],
        },
        searchTimeout: null,
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
        replyModal: false,
        form: { ...FormModel },
        inquiry: null,
        toast: useToast(),
    }),

    getters: {

    },

    actions: {
        async getInquiry(page = this.pagination.current_page, perPage = this.pagination.per_page) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route('admin.inquiry.list'), {
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

        submitReply(id) {
            axios.post(route('admin.inquiry.reply', id), this.form)
            .then(response => {
                console.log(response.data);
                this.toast.add({ severity: 'info', summary: 'Successful', detail: 'Responding to the inquiry was successful', life: 3000 });
                this.replyModal = false;
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalCenter'));
                modal?.hide();
                this.setFormData();
                router.visit(route('admin.inquiry.list'),{
                    preserveState: false,
                  });
            })
            .catch(error => {
                if (error.status === 422) {
                    this.form.errors = error.response.data.errors;
                    
                } else {
                    console.log(error.response);
                }
            });
        },

        cancelSubmit() {
            this.replyModal = false;
            this.setFormData();
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
        clearFilter() {
            this.filters = [{
                column: null,
                value: "",
            }];
            this.filterModelVisible = false;
            router.visit(route('admin.inquiry.list'));
        },

        setFormData() {
            this.form = {...FormModel };
        },

        onSearchQuery(searchValue) {
            clearTimeout(this.searchTimeout); // Clear previous timeout
            const index = this.checkIfIndexExists(this.filters);
            this.searchTimeout = setTimeout(() => {
                if (searchValue.length > 2) {
                    if (index == -1 || !index) {
                        
                        this.searchFilters.column = "name";
                        this.searchFilters.value.push(searchValue);
                        this.filters.push(this.searchFilters);
                    } else {
                        this.filters[index].value[0] = searchValue;
                    }
                } else if (searchValue.length === 0) {
                    this.filters.splice(index, 1);
                    this.searchFilters = {
                        column: "",
                        value: [],
                    }
                } else {
                    return; // Do nothing if between 1-2 characters
                }

                this.getInquiry(this.pagination.current_page);
            }, 500); // Delay API call by 500ms after typing stops
        },
        onPage(event) {
            this.getInquiry(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getInquiry();
        },
        checkIfIndexExists(array){
            return array.findIndex((obj) => obj.column === 'name');
        }
    }
})