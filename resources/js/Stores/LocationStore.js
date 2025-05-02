import { defineStore } from "pinia";
import axios from "axios";
import { useToast } from "primevue";
import { router } from "@inertiajs/core";

const FormModel = {
    errors: {},
    name: '',
};

export const useLocationStore = defineStore("locationStore", {
    state: () => ({
        list: [],
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
        async getLocations(
            page = this.pagination.current_page,
            perPage = this.pagination.per_page
        ) {
            this.pagination.current_page = page;
            this.pagination.per_page = perPage;

            const response = await axios.post(route("admin.location.list"), {
                filters: this.filters,
                sorts: this.sort,
                page: this.pagination.current_page,
                per_page: this.pagination.per_page,
            });

            if (response.data) {
                console.log(response.data);
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

        createLocation() {
            axios
                .post(route("admin.location.store"),this.form)
                .then((response) => {
                    this.form = { ...FormModel };
                    router.visit(response.data.redirectURL);
                    console.log(response.data);
                })
                .catch((error) => {
                    if (error.status == 422) {
                        this.form.errors = error.response.data.errors;
                    }
                });
        },

        updateLocation(id) {
            const formData = new FormData();
            for (let key in this.form) {
                formData.append(key, this.form[key]);
            }

            if (this.form.image) {
                formData.append("image", this.form.image);
            }

            axios
                .post(route("admin.location.update", id), formData)
                .then((response) => {
                    this.form = { ...FormModel };
                    router.visit(response.data.redirectURL);
                    console.log(response.data);
                })
                .catch((error) => {
                    if (error.status == 422) {
                        this.form.errors = error.response.data.errors;
                    }
                });
        },

        deleteLocation(id) {
            axios
                .post(route("admin.location.delete", id))
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
            if (this.location) {
                this.form = {
                    name: this.location.name || "",
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
                this.getLocations(this.pagination.current_page);
            }
        },
        onPage(event) {
            this.getLocations(event.page + 1, event.rows);
        },
        onSort(event) {
            this.sort.fieldName = event.sortField;
            this.sort.shortBy = event.sortOrder;
            this.getLocations();
        },
    },
});
