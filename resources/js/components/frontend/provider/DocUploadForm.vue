<script setup>
import { useTemplateRef, ref, watch } from 'vue';
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    doc: null,
    service_id: null,
    item_id: null,
})

const props = defineProps({
    fileSelected: Boolean,
    services: Object,
    docItem: Object,
    isShown: Boolean
})

const emit = defineEmits(['doc-selected', 'doc-saved-success']);
const docFile = ref(null)
const fileInput = useTemplateRef('fileInput')

watch(
    () => props.fileSelected, (value) => {
        if (!value) {
            docFile.value = null
            form.doc = null
        }
    }
)

watch(
    () => props.isShown, (value) => {
        if (value) {
            if (form.hasErrors) {
                form.clearErrors()
            }
    
            form.reset()
        }
    }
)

watch(
    () => props.docItem, (value) => {
        if (value) {
            form.service_id = value.service_id
            form.item_id = value.id
            docFile.value = value.document_url
        } else {
            form.service_id = null
            form.item_id = null
            docFile.value = null
        }
    }
)

function handleFile() {
    fileInput.value.click()

    fileInput.value.onchange = (event) => {
        const file = event.target.files[0]
        const reader = new FileReader()

        reader.onload = (e) => {
            docFile.value = e.target.result
        }
        reader.readAsDataURL(file)
        form.doc = file
        emit('doc-selected', docFile.value)
    }
}

function handleSubmit() {
    form.post(`/doc-upload`, {
        onSuccess: page => {
            emit('doc-saved-success')
        }
    })
}
</script>

<template>
    <form class="login-form" @submit.prevent="handleSubmit">
        <div class="form-input">
            <label>Upload document</label>
            <div class="file-upload-container" v-on:click="handleFile">
                <input type="file" accept="image/*" ref="fileInput">
                <div class="placeholder" :style="docFile ? 'display: none;' : 'display: flex;'">
                    <img src="/public/frontend_assets/images/document-upload.svg" alt="Upload icon">
                    <span>Upload your document</span>
                </div>
                <img class="preview-img" :src="docFile ?? ''" :style="docFile ? 'display: block;' : 'display: none;'">
            </div>
            <small class="text-danger errorMsg" v-if="form.errors.doc">{{ form.errors.doc }}</small>
        </div>
        <div class="form-input">
            <label>Service</label>
            <select v-model="form.service_id">
                <option value="">Select a service</option>
                <option v-for="item in services" :key="item.id" :value="item.id">{{ item.name }}</option>
            </select>
            <small class="text-danger errorMsg" v-if="form.errors.service_id">{{ form.errors.service_id }}</small>
        </div>
        <div class="form-input">
            <button type="submit">Save</button>
        </div>
    </form>
</template>