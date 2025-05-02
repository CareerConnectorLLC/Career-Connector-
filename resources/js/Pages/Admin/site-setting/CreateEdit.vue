<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">{{ props?.site_setting ? 'Update' : 'Create' }} Site Setting</h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="updateSiteSetting()">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6 mb-3">
                        <label for="address">Address</label>
                        <input type="text" id="address" v-model="form.address"
                            class="form-control border-gray-200" placeholder="Address"></input>
                        <span class="text-danger" v-if="form.errors?.address">{{ form.errors?.address }}</span>
                    </div>
                    <div class="form-group col-lg-6 mb-3">
                        <label for="email">Email</label>
                        <input type="text" id="email" v-model="form.email" class="form-control border-gray-200"
                            placeholder="Email">
                        <span class="text-danger" v-if="form.errors?.email">{{ form.errors?.email }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="phone">Phone</label>
                        <input type="number" id="phone" v-model="form.phone" class="form-control border-gray-200"
                            placeholder="Phone">
                        <span class="text-danger" v-if="form.errors?.phone">{{ form.errors?.phone }}</span>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                            <h5 class="mb-0">Social Handles</h5>
                        </div>
                    </div>
                    <div class="px-5 py-3">
                        <template v-for="(socials, index) in social_handles" :key="socials">
                            <div class="row mb-4">
                                <div class="col-lg-4"> 
                                    <label for="platform_name">Social Handle Name</label>
                                    <input type="text" id="platform_name" v-model="socials.platform"
                                        class="form-control border-gray-200" placeholder="Social Handle Name">
                                    <div class="text-danger" v-if="form.errors['social_handles.'+index+'.platform']">
                                        {{ form.errors['social_handles.'+index+'.platform'] }}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="url">Social Handle URL</label>
                                    <input type="text" id="url" v-model="socials.url"
                                        class="form-control border-gray-200" placeholder="Social Handle URL">
                                    <div class="text-danger" v-if="form.errors['social_handles.'+index+'.url']">
                                        {{ form.errors['social_handles.'+index+'.url'] }}
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="icon">Social Handle Icon</label>
                                    <input type="text" id="icon" v-model="socials.icon"
                                        class="form-control border-gray-200" placeholder="Social Handle Icon URL">
                                    <div class="text-danger" v-if="form.errors['social_handles.'+index+'.icon']">
                                        {{ form.errors['social_handles.'+index+'.icon'] }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button :loading="processing" type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2" label="Submit" />
                        <Link :href="route('admin.dashboard')" class="btn btn-outline-primary waves-effect" as="button"
                            type="button">
                        Cancel</Link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue'
import { useForm, router } from '@inertiajs/vue3'


const props = defineProps({
    site_setting: Object,
});

const processing = ref(false);

const arr1 = props.site_setting ? props.site_setting.social_handles : null;
const social_handles = JSON.parse(arr1);


const form = useForm({
    address: props.site_setting.address || '',
    phone: props.site_setting.phone || '',
    email: props.site_setting.email || '',
    social_handles: [],
});

onMounted(() => {
    nextTick(() => {
        const pageName = props?.site_setting ? 'Update' : 'Create';
        const breadcrumbs = [
            { title: 'Site Setting' },
            {
                title: pageName,
            }
        ];

        emit.emit('pageName', pageName, breadcrumbs);
    });
});


function updateSiteSetting() {
    processing.value = true;
    form.social_handles = social_handles;
    form.post(route('admin.site-setting.update'), { preserveState: true,
        onFinish: () => {
            processing.value = false;
        }
     });
}

</script>