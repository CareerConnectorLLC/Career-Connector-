<script setup>
import { computed, onMounted } from 'vue'
import { usePage, useForm, Link } from '@inertiajs/vue3'
import Onboarding from '../../../../components/frontend/provider/Onboard.vue'

const page = usePage()

const form = useForm({
    image: {}
})

const services = computed(() => page.props.services)

const documents = computed(() => page.props.documents)

onMounted(() => {
    services.value.forEach(service => {
        form.image[service.id] = ''
    })

    if (documents.value) {
        Object.keys(documents.value.image).forEach(async (key) => {
            form.image[key] = await urlToFile(documents.value.image[key], key)
        })
    }

    const uploadContainer = Array.from(document.querySelectorAll(".file-upload-container"))
    
    uploadContainer.forEach((element, index) => {
        const input = element.querySelector("input")
        const previewImg = element.querySelector(".preview-img")
        const placeholder = element.querySelector(".placeholder")
        
        element.addEventListener("click", () => input.click())
        
        input.addEventListener('change', e => {
            const file = e.target.files[0]
            const reader = new FileReader()
            
            reader.onload = e => {
                previewImg.src = e.target.result
                previewImg.style.display = "block"
                placeholder.style.display = "none"
            }

            reader.readAsDataURL(file)
            form.image[services.value[index]['id']] = file
        })
    })
})

const urlToFile = async (url, key) => {
    try {
        const response = await fetch(url);
        const blob = await response.blob();
        const contentType = blob.type || 'application/octet-stream';
        return new File([blob], `image_file_${key}`, { type: contentType })
    } catch (error) {
        //
    }
}
</script>

<template>
    <Onboarding :currentUrl="$page.props.current_url">
        <div class="login-form">
            <div class="login-head">
                <h1 v-text="`Upload documents`"></h1>
                <p v-text="`Lorem ipsum dolor, sit amet consectetur adipisicing.`"></p>
            </div>
            <form @submit.prevent="form.post(`/onboard/provider/document-upload`, { replace: true })">
                <h2>Certifications &amp; documentation</h2>
                <template v-for="(service, index) in services" :key="service.id">
                    <div class="form-input">
                        <label>Upload document ({{ service.name }})</label>
                        <div class="file-upload-container">
                            <input type="file" accept="image/*">
                            <div class="placeholder" v-show="!documents">
                                <img src="/public/frontend_assets/images/document-upload.svg" alt="Upload icon">
                                <span>Upload your document</span>
                            </div>
                            <img v-bind:style="documents ? `display: block;` : `display: none;`"
                                :src="documents?.image[service.id] ?? ``" class="preview-img"
                                :id="`img-preview-${service.id}`">
                        </div>
                        <small class="text-danger" v-if="form.errors[`image.${service.id}`]">
                            {{ form.errors[`image.${service.id}`] }}
                        </small>
                    </div>
                </template>
                <div class="form-input">
                    <button type="submit" :disabled="form.processing">Next</button>
                </div>
            </form>
            <div class="btn-wrapper">
                <Link class="back-btn float-start" href="/onboard/provider/service-details">Back</Link>
                <Link href="/onboard/provider/availability" class="back-btn float-end">Skip</Link>
            </div>
        </div>
    </Onboarding>
</template>