
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

export const useUserStore = defineStore('userStore', {
    state: () => ({
        list: [],
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
        form: {...FormModel},
        user: null,
        genderObj: [
            {value: 'male', label: 'Male'},
            {value: 'female', label: 'Female'},
            {value: 'others', label: 'Others'}
        ]
    }),
    getters: {

    },
    actions: {
        async getUsers(page = this.pagination.current_page, perPage = this.pagination.per_page) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route('admin.users.list'), {
                filters: this.filters,
                sorts: this.sort,
                page: this.pagination.current_page,
                per_page: this.pagination.per_page,
            });

            if (response.data) {
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

        createUser() {
            const formData = new FormData();
            for (let key in this.form) {
                formData.append(key, this.form[key]);
            }
            if (this.form.profile_photo) {
                formData.append('profile_photo', this.form.profile_photo);
            }
            axios.post(route('admin.users.store'), formData)
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

        editUser(id) {
            const formData = new FormData();
            for (let key in this.form) {
                formData.append(key, this.form[key]);
            }
            if (this.form.profile_photo) {
                formData.append('profile_photo', this.form.profile_photo);
            }

            axios.post(route('admin.users.update', id), formData)
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

        deleteUser(id) {
            axios.post(route('admin.users.delete', id))
                .then(response => {
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    console.log(error);
                });
        },
        changeStatus(id) {
            axios.post(route('admin.users.change-status', id))
                .then(response => {
                    router.visit(response.data.redirectUrl);
                })
                .catch(error => {
                    console.log(error);
                });
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

                this.getUsers(this.pagination.current_page);
            }, 500); // Delay API call by 500ms after typing stops
        },

        onSelectFilter() {
            this.getUsers(this.pagination.current_page);
            this.filterModelVisible = false;
        },
        onPage(event) {
            this.getUsers(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getUsers();
        },
        clearFilter() {
            this.filters = [{
                column: null,
                value: "",
            }];
            this.filterModelVisible = false;
            router.visit(route('admin.users.list'));
        },
        setFormData() {
            if(this.user)
            {
                this.form = {
                    first_name: this.user.first_name || '',
                    middle_name: this.user.middle_name || '',
                    last_name: this.user.last_name || '',
                    email: this.user.email || '',
                    phone: this.user.phone || '',
                    profile_photo: null,
                    active: this.user.active || '',
                    gender: this.user.gender || '',
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
