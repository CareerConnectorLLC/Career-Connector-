<script setup>
import { computed, onMounted, nextTick } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { useConfirm } from "primevue/useconfirm";

const page = usePage()
const confirm = useConfirm()
const seoSettings = page.props.seoSettings

onMounted(() => {
    nextTick(() => {
        emit.emit('pageName', 'SEO Settings', [{ title: 'Site SEO Settings', routeName: "" }]);
    });
});

function removeItem(param) {
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
            router.visit(`/admin/seo-settings/${param}`, {
                method: 'delete',
                only: ['seoSettings']
            })
        },
        reject: () => {
            //
        }
    });
}
</script>

<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
              <h5 class="mb-0">Seo Settings List</h5>
              <small class="text-muted"></small>
            </div>
            <div class="dropdown">
              <Link href="/admin/seo-settings/create" class="btn rounded-pill btn-outline-primary waves-effect">+ Add New</Link>
            </div>
        </div>
        <div class="card-body">
            <template v-if="seoSettings.data.length">
                <DataTable
                    :value="seoSettings.data"
                    paginator
                    :rows="seoSettings.per_page"
                >
                    <Column field="page_identifier" header="Page" />
                    <Column field="title" header="Title" />
                    <Column field="canonical_url" header="Canonical Url" />
                    <Column header="Action" appendTo>
                        <template #body="slotData">
                            <Link :href="`/admin/seo-settings/${slotData.data.id}/edit`">Edit</Link>
                            <a href="" class="ms-3" @click.prevent="removeItem(slotData.data.id)">Delete</a>
                        </template>
                    </Column>
                </DataTable>
            </template>

            <div class="d-flex justify-content-center align-items-center" v-else>
                <div class="text-black fw-bold" role="alert">
                    <span class="alert-icon text-secondary me-2"> <i class="ti ti-ban ti-xs"></i></span>
                    No data Found !!
                </div>
            </div>
        </div>
    </div>
</template>