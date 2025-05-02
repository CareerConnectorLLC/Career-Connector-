import { defineStore } from "pinia";
import axios from "axios";
import { useToast } from "primevue";
import { router } from "@inertiajs/core";

const FormModel = {
    errors: {},
    name: '',
    description: '',
    category_id: '',
    image: '',
};

export const useServiceStore = defineStore("serviceStore", {
    state: () => ({
        list: [],
        categories: [],
        service: null,
        filterModeVisible: false,
        filters: {
            global: null,
        },
        sort: {
            fieldName: null,
            shortBy: null,
        },
        pagination: {
            currentPage: 1,
            per_page: 10,
            total: 0,
            from: 0,
            to: 0,
        },
        form: {},
        toast: useToast(),
    }),
    getters: {},
    actions: {
        async getServies(
            page = this.pagination.current_page,
            perPage = this.pagination.per_page
        ) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route("admin.service.list"), {
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
                    to: response.data.to,
                };
            }
        },

        async getCategories() {
            const response = await axios.get(route("admin.service.categoryList"));
            if (response.data) {
                console.log(response.data);
                this.categories = response.data;
            }
        },

        createService() {
            const formData = new FormData();
            for (let key in this.form) {
                formData.append(key, this.form[key]);
            }

            if (this.form.image) {
                formData.append("image", this.form.image);
            }

            axios
                .post(route("admin.service.store"), formData)
                .then((response) => {
                    this.form = { ...FormModel };
                    router.visit(response.data.redirectURL);
                    console.log(response.data);
                })
                .catch((error) => {
                    if (error.status == 422) {
                        console.log(error.response);
                        this.form.errors = error.response.data.errors;
                        console.log(this.form.errors);
                    }
                });
        },

        updateService(id) {
            const formData = new FormData();
            for (let key in this.form) {
                formData.append(key, this.form[key]);
            }

            if (this.form.image) {
                formData.append("image", this.form.image);
            }

            axios
                .post(route("admin.service.update", id), formData)
                .then((response) => {
                    this.form = { ...FormModel };
                    router.visit(response.data.redirectURL);
                    console.log(response.data);
                })
                .catch((error) => {
                    if (error.status == 422) {
                        this.form.errors = error.response.data;
                    }
                });
        },

        deleteService(id) {
            axios
                .post(route("admin.service.delete", id))
                .then((response) => {
                    router.visit(response.data.redirectUrl);
                })
                .catch((error) => {
                    this.toast.add({
                        severity: "error",
                        summary: "Failed",
                        detail: "Error occured, Please try again later",
                        life: 3000,
                    });
                    console.log(error);
                });
        },

        setFormData() {
            if (this.service) {
                this.form = {
                    name: this.service.name || "",
                    description: this.service.description || "",
                    category_id: this.service.category_id || '',
                };
            } else {
                this.form = { ...FormModel };
            }
        },

        onSearchQuery() {
            if (
                this.filters.global.length > 2 ||
                this.filters.global.length === 0
            ) {
                this.getServies(this.pagination.current_page);
            }
        },
        onPage(event) {
            this.getServies(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getServies();
        },
    },
});
