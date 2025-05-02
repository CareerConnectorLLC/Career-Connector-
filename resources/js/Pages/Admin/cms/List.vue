<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
              <h5 class="mb-0">CMS List</h5>
              <small class="text-muted">Total {{ props?.cms.total }}</small>
            </div>
            <div class="dropdown">
              <Link :href="route('admin.cms.create')" class="btn rounded-pill btn-outline-primary waves-effect">+ Add CMS</Link>
            </div>
          </div>
       <div class="card-body">
            <DataTable :value="props.cms.data" lazy showGridlines @page="onPage($event)" @sort="onSort($event)"
                tableStyle="min-width: 50rem" :totalRecords="cms.total" :rows="cms.per_page"
                class="mb-4" :rowsPerPageOptions="[10, 20, 50]"
                :first="(props.cms?.current_page - 1) * props.cms?.per_page" paginator
                responsiveLayout="scroll">
               <template #header>
                    <div class="d-flex justify-content-between">
                        <div></div>
                        <div class="input-group input-group-sm w-auto">
                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search..." v-model="form.title" >
                        </div>
                    </div>
                </template>
                <Column field="title" header="Title" sortable  style="width: 90%;"/>
<!--                 
                <Column field="text_content" header="Text Content" sortable style="width: 50%;" >
                    <template #body="slotData">
                        <div v-html="slotData.data.text_content"></div>
                    </template>
                </Column>
                 -->
                <Column header="Action" appendTo>
                    <template #body="slotData">
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <Link :href="route('admin.cms.edit', slotData.data.id)" class="dropdown-item"><i class="ti ti-edit me-1"></i>
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
                        Showing {{ props.cms?.from }} to {{ props.cms?.to }} of {{ props.cms?.total }}
                    </div>
                </template>

            </DataTable>
        </div>
    </div>
    <!-- <ConfirmDialog /> -->
     <!-- test -->


</template>

<script setup>
    import { ref, onMounted, nextTick,reactive,watch } from 'vue';
    import { Link, router } from '@inertiajs/vue3';
    import { useConfirm } from "primevue/useconfirm";
    import { useToast } from "primevue/usetoast";
    import {debounce,throttle,pickBy} from "lodash";
    import ListHelper from '@/utilits/ListHelper.js';

    const confirm = useConfirm();
    const toast = useToast();
    const props = defineProps({
        cms:Object,
        filters:Object,
    });


    onMounted(() => {

        nextTick(() => {
            emit.emit('pageName', 'CMS', [{ title: 'CMS', routeName: "" }]);
        });
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
                router.post(route('admin.cms.remove', id));
                toast.add({ severity: 'info', summary: 'Confirmed', detail: 'The cms has been deleted', life: 3000 });
            },
            reject: () => {
                toast.add({ severity: 'error', summary: 'Cancelled', life: 3000 });
            }
        });
    };

const form = reactive({
    title: props.filters?.title || null,
})


    watch(form, debounce(() => {
        router.visit(route('admin.cms.list'), {
            method: 'get',
            data: pickBy(form),
            preserveState: true,
            // replace: true

        });
    }, 100));

    function onPage(event) {
        console.log(event.rows);

        ListHelper.setOnPage(event.page + 1, event.rows);
    }
    function onSort(event) {
        ListHelper.sortBy(event.sortField, event.sortOrder);
    }


</script>
