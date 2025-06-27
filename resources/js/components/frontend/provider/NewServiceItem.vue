<script setup>
import { useTemplateRef, watch, ref } from 'vue';
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    isShown: Boolean,
    services: Object,
    serviceItem: { type: Object, default: null },
    fileSelected: Boolean
})

const emit = defineEmits(['data-saved', 'image-selected']);
const picture = ref(null)
const fileInput = useTemplateRef('fileInput')
const imagePreview = useTemplateRef('preview-img')
const imgPlaceholder = useTemplateRef('img-placeholder')

const form = useForm({
    price: null,
    description: null,
    service_id: null,
    location: null,
    item_id: null,
    image: null,
})

watch(
    () => props.fileSelected, (value) => {
        if (!value) {
            picture.value = null
            form.image = null
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
    () => props.serviceItem, (value) => {
        if (value) {
            let { description, price, id, service_id, location, file_path } = value
            form.description = description
            form.price = parseInt(price/100)
            form.item_id = id
            form.location = location
            form.service_id = service_id

            picture.value = file_path ? file_path : null
        } else {
            form.description = null
            form.price = null
            form.item_id = null
            form.service_id = null
            form.location = null
        }
    }
)

function handleFile() {
    fileInput.value.click()

    fileInput.value.onchange = (event) => {
        const file = event.target.files[0]
        const reader = new FileReader()

        reader.onload = (e) => {
            picture.value = e.target.result
        }
        reader.readAsDataURL(file)
        form.image = file
        emit('image-selected')
    }
}

function saveDetails() {
    form.post(`/service-details`, {
        onSuccess: () => {
            emit('data-saved')
        }
    })
}
</script>

<template>
    <form class="login-form" @submit.prevent="saveDetails">
        <div class="form-input">
            <label>Upload service image</label>
            <div class="file-upload-container" v-on:click="handleFile">
                <input type="file" accept="image/*" ref="fileInput">
                <div class="placeholder" ref="img-placeholder" :style="picture ? 'display: none;' : 'display: flex;'">
                    <img src="/public/frontend_assets/images/document-upload.svg" alt="Upload icon">
                    <span>Upload your document</span>
                </div>
                <img class="preview-img" ref="preview-img" :src="picture ?? ''" :style="picture ? 'display: block;' : 'display: none;'">
            </div>
            <small class="text-danger errorMsg" v-if="form.errors.image">{{ form.errors.image }}</small>
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
            <label>Service price</label>
            <input type="text" v-model="form.price">
            <small class="text-danger errorMsg" v-if="form.errors.price">{{ form.errors.price }}</small>
        </div>

        <div class="form-input">
            <label>Service location</label>
            <input type="text" v-model="form.location">
            <small class="text-danger errorMsg" v-if="form.errors.location">{{ form.errors.location }}</small>
        </div>

        <div class="form-input">
            <label>Service description</label>
            <textarea v-model="form.description"></textarea>
            <small class="text-danger errorMsg" v-if="form.errors.description">{{ form.errors.description }}</small>
        </div>

        <div class="form-input">
            <button type="submit">Save</button>
        </div>
    </form>
</template>