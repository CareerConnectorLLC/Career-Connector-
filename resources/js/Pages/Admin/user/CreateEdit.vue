<template>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
                <h5 class="mb-0">{{ props?.user ? 'Update' : 'Create' }} Client</h5>
            </div>
        </div>
        <div class="card-body">
            <form @submit.prevent="submit()" enctype="multipart/form-data">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6 mb-3">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" v-model="store.form.first_name"
                            class="form-control border-gray-200" placeholder="First name">
                        <span class="text-danger" v-if="store.form.errors?.first_name">{{
                            store.form.errors?.first_name[0] }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="middle_name">Middle name</label>
                        <input type="text" id="middle_name" v-model="store.form.middle_name"
                            class="form-control border-gray-200" placeholder="Middle name">
                        <span class="text-danger" v-if="store.form.errors?.middle_name">{{
                            store.form.errors?.middle_name[0] }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" v-model="store.form.last_name"
                            class="form-control border-gray-200" placeholder="Last name">
                        <span class="text-danger" v-if="store.form.errors?.last_name">{{ store.form.errors?.last_name[0]
                            }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="email">Email</label>
                        <input type="email" id="email" v-model="store.form.email" class="form-control border-gray-200"
                            placeholder="Email">
                        <span class="text-danger" v-if="store.form.errors?.email">{{ store.form.errors?.email[0]
                            }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="phone">Phone</label>
                        <input type="number" id="phone" v-model="store.form.phone" class="form-control border-gray-200"
                            placeholder="Phone">
                        <span class="text-danger" v-if="store.form.errors?.phone">{{ store.form.errors?.phone[0]
                            }}</span>
                    </div>
                    <div class="form-group col-lg-6 mb-3">
                        <label class="form-label" for="gender">Gender</label>
                        <select class="form-select" id="gender" v-model="store.form.gender">
                            <option selected="" value="">Select Gender</option>
                            <option :value="gender.value" v-for="gender in store.genderObj" :key="gender.value">{{
                                gender.label }}</option>
                        </select>
                        <span class="text-danger" v-if="store.form.errors?.gender">{{ store.form.errors?.gender[0]
                            }}</span>
                    </div>

                    <template v-if="!props.user">
                        <div class="form-group col-lg-6 mb-3">
                            <label for="password">Password</label>
                            <Password toggleMask v-model="store.form.password" inputClass="form-control border-gray-200"
                                style="width: 100%;">
                                <template #header>
                                    <div class="font-semibold text-xm mb-4">Pick a password</div>
                                </template>
                                <template #footer>
                                    <Divider />
                                    <ul class="pl-2 ml-2 my-0 leading-normal">
                                        <li>At least one lowercase</li>
                                        <li>At least one uppercase</li>
                                        <li>At least one numeric</li>
                                        <li>Minimum 6 characters</li>
                                    </ul>
                                </template>
                            </Password>
                            <div v-if="store.form.errors?.password">
                                <div v-for="errors in store.form.errors?.password">
                                    <span class="text-danger">{{ errors }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 mb-3">
                            <label for="password_confirmation">Password Confirmation</label>
                            <Password toggleMask v-model="store.form.password_confirmation"
                                inputClass="form-control border-gray-200" style="width: 100%;">
                                <template #header>
                                    <div class="font-semibold text-xm mb-4">Pick a password</div>
                                </template>
                                <template #footer>
                                    <Divider />
                                    <ul class="pl-2 ml-2 my-0 leading-normal">
                                        <li>At least one lowercase</li>
                                        <li>At least one uppercase</li>
                                        <li>At least one numeric</li>
                                        <li>Minimum 6 characters</li>
                                    </ul>
                                </template>
                            </Password>
                            <span class="text-danger" v-if="store.form.errors?.password_confirmation">{{
                                store.form.errors?.password_confirmation[0] }}</span>
                        </div>
                        <!-- <div class="form-group col-lg-6 mb-3">
                        <label for="password">Password</label>
                        <input type="password" id="password" v-model="store.form.password" class="form-control border-gray-200" placeholder="Password">
                        <span class="text-danger" v-if="store.form.errors?.password">{{ store.form.errors?.password[0] }}</span>
                    </div>
                    <div class="form-group col-lg-6 mb-3">
                        <label for="password_confirmation">Password Confirmation</label>
                        <input type="password" id="password_confirmation" v-model="store.form.password_confirmation" class="form-control border-gray-200" placeholder="Password Confirmation">
                        <span class="text-danger" v-if="store.form.errors?.password_confirmation">{{ store.form.errors?.password_confirmation[0] }}</span>
                    </div> -->
                    </template>

                    <div class="form-group col-lg-6 mb-3">
                        <label class="form-label" for="active">Status</label>
                        <select class="form-select" id="active" v-model="store.form.active">
                            <option selected="" value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span class="text-danger" v-if="store.form.errors?.active">{{ store.form.errors?.active[0]
                            }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="profile_photo">Profile Photo</label>
                        <FilePond v-model="store.form.profile_photo"
                            :myFile="(store.user?.profile_photo_path) ? store.user?.profile_photo_url : null"
                            @removefile="handleFileRemove" />
                        <span class="text-danger" v-if="store.form.errors?.profile_photo">{{
                            store.form.errors?.profile_photo[0] }}</span>
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2"
                            :loading="store.form.processing" label="Submit" />
                        <Link :href="route('admin.users.list')" class="btn btn-outline-primary waves-effect" as="button"
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
import FilePond from '@/components/FilePond.vue'
import { useUserStore } from '@/Stores/UserStore.js'

const props = defineProps({ user: Object });
const store = useUserStore();

onMounted(() => {
    nextTick(() => {
        const pageName = 'Client Management';
        const breadcrumbs = [
            { title: 'Clients', routeName: "admin.users.list" },
            {
                title: pageName,
                routeName: ''
            }
        ];
        emit.emit('pageName', pageName, breadcrumbs);
        store.user = props?.user ? props.user : null;
        store.setFormData();
        console.log(store.form.profile_photo);
    });
});



function submit() {
    if (props.user) {
        store.editUser(props.user.id);
    } else {
        store.createUser();
    }
}


const handleFileRemove = () => {
    store.form.profile_photo = null;
    console.log(store.form?.profile_photo);
};
</script>
