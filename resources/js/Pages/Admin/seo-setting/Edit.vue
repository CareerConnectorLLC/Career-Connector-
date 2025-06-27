<script setup>
import { onMounted, nextTick, computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const page = usePage()

const seoSetting = computed(() => page.props.seoSetting)

const form = useForm({
    page_identifier: seoSetting.value.page_identifier ?? null,
    title: seoSetting.value.title ?? null,
    description: seoSetting.value.description ?? null,
    keywords: seoSetting.value.keywords ?? null,
    canonical_url: seoSetting.value.canonical_url ?? null,
})

const pageIdentifiers = computed(() => page.props.pageIdentifiers)

onMounted(() => {
    nextTick(() => {
        const pageName = "Edit SEO Settings";
        
        const breadcrumbs = [
            { title: 'SEO Settings', routeName: "admin.seo-settings.index" },
            {
                title: 'Edit SEO Setting'
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
    });
});

function handleSubmit() {
    form.patch(`/admin/seo-settings/${seoSetting.value.id}`);
}
</script>

<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0"></h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="handleSubmit()">
                <div class="row g-3">
                    <div class="mb-3">
                        <label for="page_identifier">Page Identifier (e.g., home, about-us, blog)</label>
                        <select id="page_identifier" v-model="form.page_identifier" class="form-control border-gray-200">
                            <option value="">Select Page</option>
                            <template v-for="(identifier, key) in pageIdentifiers" :key="key">
                                <option :value="key">{{ pageIdentifiers[key] }}</option>
                            </template>
                        </select>
                        <span class="text-danger" v-if="form.errors.page_identifier">{{ form.errors.page_identifier }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="title">Meta Title</label>
                        <input type="input" v-model="form.title" id="title" class="form-control border-gray-200" />
                        <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="description">Meta Description</label>
                        <textarea v-model="form.description" id="description" class="form-control border-gray-200"></textarea>
                        <span class="text-danger" v-if="form.errors.description">{{ form.errors.description }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="keywords">Meta Keywords</label>
                        <input type="input" v-model="form.keywords" id="keywords" class="form-control border-gray-200" />
                        <span class="text-danger" v-if="form.errors.keywords">{{ form.errors.keywords }}</span>
                    </div>
                    <div class="mb-3">
                        <label for="canonical_url">Canonical URL</label>
                        <input type="input" id="canonical_url" v-model="form.canonical_url" class="form-control border-gray-200" />
                        <span class="text-danger" v-if="form.errors.canonical_url">{{ form.errors.canonical_url }}</span>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2" label="Submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>