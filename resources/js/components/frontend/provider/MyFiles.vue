<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    docs: Object
})

const form = useForm()
const emit = defineEmits(['doc-selected'])

function formatDatetime(param) {
    const date = new Date(param);
    return date.toLocaleDateString()
}

function editDoc(param) {
    let item = props.docs.find((d) => d.id === param)
    emit('doc-selected', item)
}

function removeDoc(param) {
    form.delete(`/doc-upload/${param}`)
}

function showPage(param) {
    let { document_url } = param
    window.open(document_url, "_blank", "width=600,height=400");
}
</script>

<template>
    <div class="files-listing">
        <div class="my-profile-head">
            <h3>Files</h3>
            <a data-fancybox="" data-src="#upload-document" class="primary-btn" href="#url">Add new</a>
        </div>
        
        <template v-if="docs.length">
            <ul>
                <li v-for="item in docs" :key="item.id">
                    <a href="#url" @click.prevent="showPage(item)">
                        <figure>
                            <img src="/public/frontend_assets/images/pdf.svg" alt="pdf">
                        </figure>
                        <div class="pdf-detsils">
                            <div class="file-head">
                                <p class="text-capitalize" v-text="`${item.service.name} document`"></p>
                                <span v-text="formatDatetime(item.updated_at)"></span>
                            </div>
                        </div>
                    </a>
                    <div class="dot-option">
                        <a class="notification-option-btn dropdown-toggle" href="#url" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span><img src="/public/frontend_assets/images/dots.svg" alt="dots"></span>
                        </a>
                        <div class="dots-drop-dowm dropdown-menu">
                            <ul>
                                <li><a href="#url" @click.prevent="editDoc(item.id)">Edit</a></li>
                                <li><a href="#url" @click.prevent="removeDoc(item.id)">Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </template>

        <p v-else>No Documents Found</p>
    </div>
</template>