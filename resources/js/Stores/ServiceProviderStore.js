
import axios from 'axios';
import { defineStore } from 'pinia';
import { router } from '@inertiajs/vue3';

const FormModel = {
    errors: {},
    first_name: '',
    middle_name: '',
    last_name: '',
    email: '',
    phone: '',
    password: '',
    password_confirmation: '',
    profile_photo: '',
    active: '',
    gender: '',
};

export const useServiceProviderStore = defineStore('serviceProviderStore', {
    state: () => ({
        list: [],
        filterModelVisible: false,
        // filters: {
        //     global: null,
        // },
        sort: {
            fieldName: null,
            shortBy: null
        },
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
        pagination: {
            current_page: 1,
            per_page: 10,
            total: 0,
            from: 0,
            to: 0
        },
        form: {...FormModel},
        serviceProvider: null,
        genderObj: [
            {value: 'male', label: 'Male'},
            {value: 'female', label: 'Female'},
            {value: 'others', label: 'Others'}
        ]
    }),
    getters: {

    },
    actions: {
        async getServiceProviders(page = this.pagination.current_page, perPage = this.pagination.per_page) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route('admin.service-providers.list'), {
                // filters: [{
                //     column: this.filters.column,
                //     value: [this.filters.value],
                // }],
                filters:this.filters,
                sorts: this.sort,
                page: this.pagination.current_page,
                per_page: this.pagination.per_page,
            });

            if (response.data) {
                console.log(response.data);
                this.list = response.data.data; // Paginated data
                this.pagination = {
                    current_page: response.data.current_page,
                    per_page: response.data.per_page,
                    total: response.data.total,
                    from: response.data.from,
                    to: response.data.to
                };
            }
        },

        createServiceProvider() {
            const formData = new FormData();
            for (let key in this.form) {
                formData.append(key, this.form[key]);
            }
            if (this.form.profile_photo) {
                formData.append('profile_photo', this.form.profile_photo);
            }
            axios.post(route('admin.service-providers.store'), formData)
                .then(response => {
                    this.form = { ...FormModel };
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    if (error.status === 422) {
                        this.form.errors = error.response.data.errors;


                    }
                });
        },

        editServiceProvider(id) {
            const formData = new FormData();
            for (let key in this.form) {
                formData.append(key, this.form[key]);
            }
            if (this.form.profile_photo) {
                formData.append('profile_photo', this.form.profile_photo);
            }

            axios.post(route('admin.service-providers.update', id), formData)
                .then(response => {
                    this.form = { ...FormModel };
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    if (error.status === 422) {
                        this.form.errors = error.response.data.errors;
                    }
                });
        },

        deleteServiceProvider(id) {
            axios.post(route('admin.service-providers.delete', id))
                .then(response => {
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        changeStatus(id) {
            axios.post(route('admin.service-providers.change-status', id))
                .then(response => {
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    console.log(error);
                });
        },

        // onSearchQuery() {
        //     if (this.filters.value.length > 2 ) {
        //         this.filters.column = 'name';
        //         this.getServiceProviders(this.pagination.current_page);
        //     } else if (this.filters.value.length === 0) {
        //         this.filters.column = "";
        //         this.getServiceProviders(this.pagination.current_page);
        //     }
        // },

        onSearchQuery(searchValue) {
            clearTimeout(this.searchTimeout); // Clear previous timeout
            const index = this.checkIfIndexExists(this.filters);
            this.searchTimeout = setTimeout(() => {
                if (searchValue.length > 2) {
                    if (index == -1 || !index) {
                        console.log('condition 1', index);
                        
                        this.searchFilters.column = "name";
                        this.searchFilters.value.push(searchValue);
                        this.filters.push(this.searchFilters);
                    } else {
                        console.log('condition 2');
                        this.filters[index].value[0] = searchValue;
                    }
                } else if (searchValue.length === 0) {
                    console.log('condition 3');
                    this.filters.splice(index, 1);
                    this.searchFilters = {
                        column: "",
                        value: [],
                    }
                } else {
                    return; // Do nothing if between 1-2 characters
                }

                this.getServiceProviders(this.pagination.current_page);
            }, 500); // Delay API call by 500ms after typing stops
        },

        onSelectFilter() {
            this.getServiceProviders(this.pagination.current_page);
            this.filterModelVisible = false;
        },
        onPage(event) {
            this.getServiceProviders(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getServiceProviders();
        },
        clearFilter() {
            this.filters = [{
                column: null,
                value: "",
            }];
            this.filterModelVisible = false;
            router.visit(route('admin.service-providers.list'));
        },
        setFormData() {
            if(this.serviceProvider)
            {
                this.form = {
                    first_name: this.serviceProvider.first_name || '',
                    middle_name: this.serviceProvider.middle_name || '',
                    last_name: this.serviceProvider.last_name || '',
                    email: this.serviceProvider.email || '',
                    phone: this.serviceProvider.phone || '',
                    profile_photo: null,
                    active: this.serviceProvider.active || '',
                    gender: this.serviceProvider.gender || '',
                }
            }
            else{
                this.form ={...FormModel };
            }
        },

        checkIfIndexExists(array){
            return array.findIndex((obj) => obj.column === 'name');
        }
    }
});
