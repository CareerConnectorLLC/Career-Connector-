
import axios from 'axios';
import { defineStore } from 'pinia';

export const useUserStore = defineStore('userStore', {
    state: () => ({
        list: [],
        filterModelVisible: false,
        filters: {
            global: null,
            active: null
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

        onSearchQuery() {
            if (this.filters.global.length > 2 || this.filters.global.length === 0) {
                this.getAdmins(this.pagination.current_page);
            }
        },
        onSelectFilter() {
            this.getAdmins(this.pagination.current_page);
        },
    }
});
