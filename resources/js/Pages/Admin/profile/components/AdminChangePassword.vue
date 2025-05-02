<template>
    <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0">Change password</h5>
        </div>
      </div>
        <div class="card-body">
            <form @submit.prevent="submit">
                <div class="form-group validated row">
                    <!-- {{ $form }} -->
                    <div class="form-group col-lg-6 mb-3">
                        <label for="old_password">Old Password <span class="text-danger">*</span></label>
                        <div class="input-group input-group-merge has-validation">
                            <input :type="showCurrentPassword ? 'text' : 'password'" id="old_password" v-model="form.old_password" class="form-control" placeholder="Current Password">
                                <span class="input-group-text cursor-pointer">
                                    <i @click="showCurrentPassword = !showCurrentPassword" class="ti" :class="{ 'ti-eye-off': !showCurrentPassword, 'ti-eye': showCurrentPassword }"></i>
                                </span>
                        </div>
                        <span class="text-danger" v-if="form.errors.old_password">{{ form.errors.old_password }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="new_password">New password <span class="text-danger">*</span></label>
                        <Password toggleMask v-model="form.new_password" inputClass="form-control border-gray-200" style="width: 100%;">
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
                        <span class="text-danger" v-if="form.errors.new_password">{{ form.errors.new_password }}</span>
                    </div>

                    <div class="form-group col-lg-6 mb-3">
                        <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                        <Password toggleMask v-model="form.confirm_password" inputClass="form-control border-gray-200" style="width: 100%;">
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
                        <span class="text-danger" v-if="form.errors.confirm_password">{{ form.errors.confirm_password }}</span>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button class="btn btn-primary kt-btn btn-sm kt-btn--icon button-fx me-2" :disabled="form.processing" :isLoading="form.processing">Submit</button>
                        <Link href="/admin/dashboard" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" as="button" type="button">Cancel</Link>
                    </div>
                </div>
            </form>
        </div>
</template>

<script setup>
import { ref, watch, reactive, onMounted } from 'vue';
import { useForm,router } from '@inertiajs/vue3'


const form = useForm({
    old_password: null,
    new_password: null,
    confirm_password: null,
})

const props = defineProps({
   errors:Object
})

const showCurrentPassword = ref(false);
const showNewPassword = ref(false);
const showConfirmPassword = ref(false);


const newPassword = ref();
function submit() {

    form.post(route('admin.change-password'),{
        preserveScroll: true,
        // onError: (errors) => {
        //     newPassword.value.focus();
        //     console.log(errors);
        // },
     });
}

</script>

<style>

</style>
