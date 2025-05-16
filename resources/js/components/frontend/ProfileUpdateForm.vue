<script setup>
import { useTemplateRef, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Datepicker from 'vanillajs-datepicker/Datepicker'

const emit = defineEmits(['profile-update-success'])
const previewImg = useTemplateRef('previewImg')
const datePicker = useTemplateRef('datePicker')
const removeBtn = useTemplateRef('removeBtn')
const uploadErr = useTemplateRef('uploadErr')

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
})

onMounted(() => {
    const datepicker = new Datepicker(datePicker.value)

    if (props.user.profile && props.user.profile.birth_day) {
        datepicker.setDate(props.user.profile.birth_day)
    }

    datePicker.value.addEventListener('changeDate', (e) => {
        let date = e.detail.date
        form.date_of_birth = date.toLocaleDateString('en-US')
    })

    if (props.user.profile_photo_url) {
        document.getElementById('imagePreview').style.display = 'block'
        removeBtn.value.style.display = 'inline-block'
        previewImg.value.src = props.user.profile_photo_url
    }
})

const form = useForm({
    name: props.user.full_name,
    email: props.user.email,
    location: props.user.location,
    phone: props.user.phone,
    country: (props.user.profile) ? props.user.profile.country : null,
    state: (props.user.profile) ? props.user.profile.state : null,
    zip_code: (props.user.profile) ? props.user.profile.zip_code : null,
    date_of_birth: (props.user.profile) ? props.user.profile.birth_day : null,
    profile_pic: null,
})

const updateProfile = () => {
    form.post(`/client-profile`, {
        preserveScroll: true,
        onSuccess: () => {
            emit('profile-update-success')
        }
    })
}

const imageChosen = (event) => {
    const previewContainer = document.getElementById('imagePreview')
    const fileTypes = ['image/png', 'image/jpeg']
    const file = event.target.files[0]

    if (uploadErr.value.innerText != '') {
        uploadErr.value.innerText = ''
    }

    if (file) {
        if (!fileTypes.includes(file.type)) {
            document.getElementById('fileInput').value = ''
            uploadErr.value.innerText = `Only images in jpeg and png are supported`
            return
        }
        
        const reader = new FileReader()
    
        reader.onload = (e) => {
            previewImg.value.src = e.target.result
            previewContainer.style.display = 'block'
            removeBtn.value.style.display = 'inline-block'
            form.profile_pic = file
        }
        reader.readAsDataURL(file);
    }
}

const removeImage = () => {
    const previewContainer = document.getElementById('imagePreview')
    const fileInput = document.getElementById('fileInput')
    fileInput.value = ''
    previewImg.value.src = ''
    previewContainer.style.display = 'none'
    form.profile_pic = null
    removeBtn.value.style.display = 'none'
}
</script>

<template>
    <form class="login-form" @submit.prevent="updateProfile">
        <div class="form-input">
            <div class="custom-file-input">
                <div class="upload-container">
                    <div class="image-preview" id="imagePreview">
                        <img id="previewImg" ref="previewImg" src="" alt="Profile Preview">
                    </div>
                    <label for="fileInput" class="file-label">Upload Image</label>
                    <input type="file" id="fileInput" @change="imageChosen($event)" class="file-input" accept="image/*">
                    <button id="removeBtn" ref="removeBtn" @click.prevent="removeImage" class="remove-btn">Remove</button>
                </div>
                <p>Upload your image</p>
            </div>
            <p class="errorMsg" ref="uploadErr"></p>
        </div>

        <div class="form-input">
            <label>Name</label>
            <input v-model="form.name" class="user" type="text">
            <small class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</small>
        </div>
        <div class="form-input">
            <label>Email address</label>
            <input v-model="form.email" type="email">
            <small class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</small>
        </div>
        <div class="form-input">
            <label>Phone no.</label>
            <input v-model="form.phone" class="phone" type="tel">
            <small class="text-danger" v-if="form.errors.phone">{{ form.errors.phone }}</small>
        </div>
        <div class="form-input">
            <label>Full address</label>
            <input v-model="form.location" class="location" type="text">
            <small class="text-danger" v-if="form.errors.location">{{ form.errors.location }}</small>
        </div>
        <div class="form-input">
            <div class="form-input-row row">
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>Country</label>
                        <select v-model="form.country">
                            <option>Select</option>
                            <option value="United States">USA</option>
                            <option value="United Kingdom">UK</option>
                        </select>
                        <small class="text-danger" v-if="form.errors.country">{{ form.errors.country }}</small>
                    </div>
                </div>
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>State</label>
                        <select v-model="form.state">
                            <option>Select</option>
                            <option value="New York">New York</option>
                            <option value="Washington">Washington</option>
                            <option value="London">London</option>
                        </select>
                        <small class="text-danger" v-if="form.errors.state">{{ form.errors.state }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-input">
            <div class="form-input-row row">
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>ZIP Code</label>
                        <input v-model="form.zip_code" type="text">
                        <small class="text-danger" v-if="form.errors.zip_code">{{ form.errors.zip_code }}</small>
                    </div>
                </div>
                <div class="col-md-6 form-input-col">
                    <div class="form-input">
                        <label>Date of birth</label>
                        <div class="form-input-inner commonDatepickerStyle">
                            <input type="text" ref="datePicker">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-input">
            <button type="submit">Save</button>
        </div>
    </form>
</template>

<style scoped>
@import url('https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css');
</style>