<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">Inquiries List</h5>
                <small class="text-muted">Total {{ store?.pagination.total }}</small>
            </div>
        </div>
        <div class="card-body">
            <DataTable :value="store.list" lazy showGridlines @page="store.onPage" @sort="store.onSort"
                tableStyle="min-width: 50rem" :totalRecords="store.pagination.total" :rows="store.pagination.per_page"
                class="mb-4" :rowsPerPageOptions="[10, 20, 50]"
                :first="(store?.pagination.current_page - 1) * store?.pagination.per_page" paginator
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
                                <label for="">Filter by Inquiry Date</label>
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

                <Column field="name" header="Name" sortable>
                    <template #body="slotData">
                        <h6 class="mb-0">
                            {{ slotData.data.name }}
                        </h6>
                        <small>on {{dayjs(slotData.data.created_at).format('MMM D, YYYY')}} </small>
                    </template>
                </Column>
                <Column field="message" header="Message" />
                <Column field="email" header="Email" />
                <Column field="phone" header="Phone" />
                <Column field="status" header="Status" sortable style="min-width: 12%;">
                    <template #body="slotData">
                        <div class="pb-2">
                            <span @click="confirmChangeStatus(slotData.data.id)" class="badge cursor-pointer"
                                :class="{'bg-label-warning': (slotData.data.status === 'Pending') , 'bg-label-success': (slotData.data.status === 'Replied')}">
                                {{ slotData.data.status }}
                            </span>
                        </div>
                        <small v-if="slotData.data.reply">on {{dayjs(slotData.data.reply?.created_at).format('MMM D, YYYY')}} </small>
                    </template>
                </Column>
                <Column header="Action" appendTo>
                    <template #body="slotData">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <Link class="dropdown-item" :href="route('admin.inquiry.show', slotData.data.id)"><i
                                    class="ti ti-eye me-1"></i> View Details</Link>
                                <button v-if="slotData.data.status === 'Pending'" class="dropdown-item"
                                    @click="store.form.Inquiry_id = slotData.data.id" data-bs-toggle="modal"
                                    data-bs-target="#modalCenter">
                                    <Icon class="me-1" icon="ri:reply-line" width="22" height="22" />Reply
                                </button>
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


                <template #paginatorstart>
                    <div class="px-3">
                        Showing {{ store.pagination.from }} to {{ store.pagination.to }} of {{ store.pagination.total }}
                    </div>
                </template>

            </DataTable>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">Reply</h5>
                        <button @click="store.cancelSubmit()" type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <label for="exampleFormControlTextarea1" class="form-label fs-6">Type Your
                                Reply Below</label>
                            <textarea v-model="store.form.reply" class="form-control" id="exampleFormControlTextarea1"
                                rows="5"></textarea>
                            <span class="text-danger" v-if="store.form.errors?.reply">{{ store.form.errors?.reply[0]
                                }}</span>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="store.cancelSubmit()" type="button" class="btn btn-label-secondary"
                            data-bs-dismiss="modal">
                            Close
                        </button>
                        <button @click="store.submitReply(store?.form.Inquiry_id)" type="button"
                            class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ConfirmDialog />
</template>

<script setup>
import { ref, onMounted, nextTick, reactive } from 'vue';
import { useInquiryStore } from '@/Stores/InquiryStore';
import { Link, router } from '@inertiajs/vue3';
import { useConfirm } from "primevue/useconfirm";
import DatePicker from 'primevue/datepicker';
import Select from 'primevue/select';
import dayjs from "dayjs";



const confirm = useConfirm();

const store = useInquiryStore();
const searchValue = ref('');
const status = [
        {
            name: 'Replied',
            value: 'Replied',
        },
        {
            name: 'Pending',
            value: 'Pending',
        }

    ];
    const filters = reactive({
        name: '',
        status: null,
        dates: []
    })



onMounted(() => {
    nextTick(() => {
        emit.emit('pageName', 'Inquiry Management', [{ title: 'Inquiries', routeName: "" }]);
        store.getInquiry();
    });
});

const confirmDelete = (id) => {
    confirm.require({
        group: 'headless',
        message: 'Are you sure you want to delete?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        rejectstore: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptstore: {
            label: 'Yes'
        },
        accept: () => {
            store.deleteCategory(id);
        },
        reject: () => {
            store.toast.add({ severity: 'error', summary: 'Cancelled', life: 3000 });
        }
    });
};

function applyFilters() {
    if (filters.status !== null) {
        const index = store.filters.findIndex((obj) => obj.column === 'status');
        console.log(index);
        if (index == -1 || !index) {
            store.filters.push({
                column: 'status',
                value: [filters.status]
            });
        } else {
            store.filters[index].value = [filters.status];
        }
    }

    if (filters.dates.length > 0) {
        const index = store.filters.findIndex((obj) => obj.column === 'created_at');
        if (index == -1 || !index) {
            store.filters.push({
                column: 'created_at',
                value: filters.dates
            });
        } else {
            store.filters[index].value = filters.dates;
        }
    }
    store.getInquiry();
}



</script>