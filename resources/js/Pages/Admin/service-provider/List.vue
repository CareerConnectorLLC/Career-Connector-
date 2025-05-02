<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
              <h5 class="mb-0">Service Provider List</h5>
              <small class="text-muted">{{ store.pagination.total}} Service Provider</small>
            </div>
            <div class="dropdown">
              <Link :href="route('admin.service-providers.create')" class="btn rounded-pill btn-outline-primary waves-effect">+ Add Service Provider</Link>
            </div>
          </div>
        <div class="card-body">
            <DataTable :value="store.list" lazy showGridlines @page="store.onPage" @sort="store.onSort"
                tableStyle="min-width: 50rem" :totalRecords="store.pagination.total" :rows="store.pagination.per_page"
                class="mb-4" :rowsPerPageOptions="[10, 20, 50]"
                :first="(store.pagination.current_page - 1) * store.pagination.per_page" paginator
                responsiveLayout="scroll">
                <template #header>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="d-flex gap-2 align-items-end">
                            <!--
                        <button
                            class="btn btn-xs btn-outline-primary waves-effect d-flex align-items-center justify-content-between"
                            @click="store.filterModelVisible = true">
                            <Icon icon="bx:filter-alt" width="18" height="18" />
                            <span class="mx-2">Filter
                                ({{ Object.values(store.filters).filter(value => (value !== null && value !=='')).length}})
                            </span>
                        </button>
                         -->
                            <div class="d-flex flex-column">
                                <label for="">Filter by Status</label>
                                <Select v-model="filters.status" :options="status" optionLabel="name"
                                    optionValue="value" placeholder="Select a Status" class="w-full" />
                            </div>
                            <div class="d-flex flex-column">
                                <label for="">Filter by Registration Date</label>
                                <DatePicker v-model="filters.dates" selectionMode="range" dateFormat="dd/mm/yy"
                                    placeholder="Enter a Date Range" />
                            </div>

                            <Button size="small" label="Apply" @click="applyFilters()" />
                            <Button size="small" label="Clear Filters" @click="store.clearFilter()" />
                        </div>
                        <div class="input-group input-group-sm w-auto h-px-40">
                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" placeholder="Name or Email" v-model="searchValue"
                                @input="store.onSearchQuery(searchValue)">
                        </div>
                    </div>
                </template>

                <template #paginatorstart>
                    <div class="px-3">
                        Showing {{ store.pagination.from }} to {{ store.pagination.to }} of {{ store.pagination.total }}
                    </div>
                </template>


                <Column field="full_name" header="Name" sortable >
                    <template #body="slotData">
                        <div class="d-flex flex-wrap">
                            <div class="avatar me-3">
                                <img :alt="slotData.data.full_name" :src="slotData.data.profile_photo_url"
                                    class="rounded-circle">
                            </div>
                            <div>
                                <h6 class="mb-0">
                                    <Link :href="'#'">
                                    {{ slotData.data.full_name }}
                                    </Link>
                                </h6>
                                <small>Member since {{dayjs(slotData.data.created_at).format('MMM D, YYYY')}} </small>
                            </div>
                        </div>
                    </template>
                </Column>
                <Column field="email" header="Email" sortable />
                <Column field="phone" header="Phone" sortable />
                <Column field="active" header="Status" sortable>
                    <template #body="slotData"><span @click="confirmChangeStatus(slotData.data.id)" class="badge cursor-pointer" 
                        :class="{'bg-label-danger': (slotData.data.active != 1) , 'bg-label-success': (slotData.data.active == 1)}">
                        {{(slotData.data.active == 1) ? 'Active' : 'Suspended'}}</span></template>
                </Column>
                <Column header="Action" appendTo>
                    <template #body="slotData">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <Link class="dropdown-item" :href="route('admin.service-providers.show', slotData.data.id)"><i class="ti ti-eye me-1"></i> View Details</Link>
                                <Link class="dropdown-item" :href="route('admin.service-providers.edit', slotData.data.id)"><i class="ti ti-edit me-1"></i> Edit</Link>
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
    import { ref, onMounted, reactive, nextTick } from 'vue';
    import { useServiceProviderStore } from '@/Stores/ServiceProviderStore';
    import { useConfirm } from "primevue/useconfirm";
    import { useToast } from "primevue/usetoast";
    import Button from 'primevue/button';

    import dayjs from "dayjs";

    const store = useServiceProviderStore();
    const confirm = useConfirm();
    const toast = useToast();
    const searchValue = ref('');
    const status = [
        {
            name: 'Active',
            value: 1,
        },
        {
            name: 'Suspended',
            value: 0,
        }

    ];
    const filters = reactive({
        name: '',
        status: null,
        dates: []
    })


    onMounted(() => {
        nextTick(() => {
            emit.emit('pageName', 'Service Provider Management', [{ title: 'Service Providers', routeName: "" }]);
        });
        store.getServiceProviders();
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
                store.deleteServiceProvider(id);
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

    function applyFilters() {
    if (filters.status !== null) {
        const index = store?.filters.findIndex((obj) => obj.column === 'active');
        console.log(index);
        if (index == -1 || !index) {
            store.filters.push({
                column: 'active',
                value: [filters.status]
            });
        } else {
            store.filters[index].value = [filters.status];
        }
    }

    if (filters.dates.length > 0) {
        const index = store?.filters.findIndex((obj) => obj.column === 'created_at');
        if (index == -1 || !index) {
            store.filters.push({
                column: 'created_at',
                value: filters.dates
            });
        } else {
            store.filters[index].value = filters.dates;
        }
    }
    store.getServiceProviders();
}

</script>
