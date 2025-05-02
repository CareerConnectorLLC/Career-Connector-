<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
              <h5 class="mb-0">Blog Category List</h5>
              <small class="text-muted">{{ store.pagination.total}} Blog Categories</small>
            </div>
            <div class="dropdown">
              <Link :href="route('admin.blog-category.create')" class="btn rounded-pill btn-outline-primary waves-effect">+ Add Blog Category</Link>
            </div>
          </div>
        <div class="card-body">
            <DataTable :value="store.list" lazy showGridlines @page="store.onPage" @sort="store.onSort"
                tableStyle="min-width: 50rem" :totalRecords="store.pagination.total" :rows="store.pagination.per_page"
                class="mb-4" :rowsPerPageOptions="[10, 20, 50]"
                :first="(store.pagination.current_page - 1) * store.pagination.per_page" paginator
                responsiveLayout="scroll">
                <template #header>
                    <div class="d-flex justify-content-between">
                        <div class="input-group input-group-sm w-auto">
                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." v-model="store.filters.global" @input="store.onSearchQuery()">
                        </div>
                    </div>
                </template>

                <template #paginatorstart>
                    <div class="px-3">
                        Showing {{ store.pagination.from }} to {{ store.pagination.to }} of {{ store.pagination.total }}
                    </div>
                </template>

                <Column field="name" header="Category Name" sortable style="width: 70%;" />
                <Column field="active" header="Status" sortable>
                    <template #body="slotData"><span @click="confirmChangeStatus(slotData.data.id)" class="badge cursor-pointer" :class="{'bg-label-danger': (slotData.data.active != 1) , 'bg-label-success': (slotData.data.active == 1)}">{{(slotData.data.active == 1) ? 'Active' : 'Inactive'}}</span></template>
                </Column>
                <Column header="Action" appendTo>
                    <template #body="slotData">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <!-- <Link class="dropdown-item" :href="'#'"><i class="ti ti-eye me-1"></i> View Details</Link> -->
                                <Link class="dropdown-item" :href="route('admin.blog-category.edit', slotData.data.id)"><i class="ti ti-edit me-1"></i> Edit</Link>
                                <button class="dropdown-item" @click="confirmDelete(slotData.data.id)"><i class="ti ti-trash me-1"></i>Delete</button>
                            </div>
                        </div>
                    </template>

                </Column>
                <template #empty>
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="text-black fw-bold" role="alert">
                            <span class="alert-icon text-secondary me-2"> <i class="ti ti-ban ti-xs"></i></span>
                            No data Found !!
                        </div>
                    </div>
                </template>

            </DataTable>
        </div>
    </div>


    <Drawer v-model:visible="store.filterModelVisible" header="Filter" position="right">
        <form class="dt_adv_search" method="POST">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">User Status</label>
                            <Select v-model="store.filters.active" showClear :options="[{name : 'Active', value: 1}, {name : 'Inactive', value: 0}]" optionLabel="name" optionValue="value" placeholder="User Status" class="form-control p-0" />
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary me-2" @click="store.clearFilter()">
                            <Icon class="me-1" icon="mdi:clear-outline" width="18" height="18" />Clear
                        </button>
                        <button type="button" class="btn btn-primary" @click="store.onSelectFilter()">
                            <Icon class="me-1" icon="mynaui:search-plus" width="18" height="18" /> Search
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </Drawer>
    <!-- <ConfirmDialog /> -->

</template>

<script setup>
    import { ref, onMounted, nextTick } from 'vue';
    import { useConfirm } from "primevue/useconfirm";
    import { useBlogCategoryStore } from '@/Stores/BlogCategoryStore';

    const store = useBlogCategoryStore();
    const confirm = useConfirm();


    onMounted(() => {
        nextTick(() => {
            emit.emit('pageName', 'Blog Category Management', [{ title: 'Blog Categories', routeName: "" }]);
        });
        store.getBlogCategories();
    });

    const confirmDelete = (id) => {
        confirm.require({
            group: 'headless',
            message: 'Are you sure you want to delete?',
            header: 'Confirmation',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: 'Yes'
            },
            accept: () => {
                store.deleteBlog(id);
            },
            reject: () => {
            }
        });
    };

    const confirmChangeStatus = (id) => {
        confirm.require({
            group: 'headless',
            message: 'Are you sure you want to Change Status?',
            header: 'Confirmation',
            icon: 'pi pi-exclamation-triangle',
            rejectProps: {
                label: 'Cancel',
                severity: 'secondary',
                outlined: true
            },
            acceptProps: {
                label: 'Yes'
            },
            accept: () => {
                store.changeStatus(id);
            },
            reject: () => {
            }
        });
    };

</script>
