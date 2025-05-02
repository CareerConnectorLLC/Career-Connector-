<template>
    <div class="card">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0">Service List</h5>
          <small class="text-muted">Total {{ store?.pagination.total }}</small>
        </div>
        <div class="dropdown">
          <Link :href="route('admin.service.create')" class="btn rounded-pill btn-outline-primary waves-effect">+ Add Service</Link>
        </div>
      </div>
   <div class="card-body">
        <DataTable :value="store.list" lazy showGridlines @page="store.onPage" @sort="store.onSort"
            tableStyle="min-width: 50rem" :totalRecords="store.pagination.total" :rows="store.pagination.per_page"
            class="mb-4" :rowsPerPageOptions="[10, 20, 50]"
            :first="(store?.pagination.current_page - 1) * store?.pagination.per_page" paginator
            responsiveLayout="scroll">
           <template #header>
                <div class="d-flex justify-content-between">
                    <div></div>
                    <div class="input-group input-group-sm w-auto">
                        <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control" placeholder="Search..." v-model="store.filters.global" @input="store.onSearchQuery()">
                    </div>
                </div>
            </template>

            <Column field="name" header="Service Name" sortable  style="width: 40%;"/>
        <!--  
            <Column field="answer" header="Answer" sortable style="width: 50%;" >
                <template #body="slotData">
                    <div v-html="slotData.data.answer"></div>
                </template>
            </Column>
        -->
        <Column field="category_name" header="Category" sortable >
                    <template #body="slotData">
                        <div class="d-flex flex-wrap">
                            <div>
                                <h6 class="mb-0">
                                    <Link :href="'#'">
                                    {{ slotData.data.category?.name }}
                                    </Link>
                                </h6>
                            </div>
                        </div>
                    </template>
                </Column>
                <Column field="expertise" header="Expertises" >
                    <template #body="slotData">
                        <div class="d-flex gap-1">
                            <span v-for="expertise in slotData.data.expertises" class="badge bg-label-info">{{ expertise.name }}</span>
                        </div>
                    </template>
                </Column>
                <Column field="active" header="Status" sortable>
                    <template #body="slotData"><span class="badge cursor-pointer" :class="{'bg-label-danger': (slotData.data.active != 1) , 'bg-label-success': (slotData.data.active == 1)}">{{(slotData.data.active == 1) ? 'Active' : 'Inactive'}}</span></template>
                </Column>
            <Column header="Action" appendTo>
                <template #body="slotData">
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu">
                            <Link class="dropdown-item"
                                :href="'#'"><i
                                class="ti ti-eye me-1"></i> View Details</Link>
                            <Link :href="route('admin.service.edit', slotData.data.id)" class="dropdown-item"><i class="ti ti-edit me-1"></i>
                                Edit</Link>
                            <button class="dropdown-item" @click="confirmDelete(slotData.data.id)"><i class="ti ti-trash me-1"></i>
                                Delete</button>
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
</div>
<ConfirmDialog />
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { useServiceStore } from '@/Stores/ServiceStore';
import { Link, router } from '@inertiajs/vue3';
import { useConfirm } from "primevue/useconfirm";

const confirm = useConfirm();

const store = useServiceStore();



onMounted(() => {
    nextTick(() => {
        emit.emit('pageName', 'Services', [{ title: 'Services', routeName: "" }]);
        store.getServies();
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
            store.deleteService(id);
        },
        reject: () => {
            store.toast.add({ severity: 'error', summary: 'Cancelled', life: 3000 });
        }
    });
};

</script>