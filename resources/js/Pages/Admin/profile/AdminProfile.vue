<template>
    <Head title="My profile"/>

    <div class="row mb-5">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0">Profile</h5>
        </div>

      </div>
      <div class="card-body">
          <form @submit.prevent="submit">
              <div class="form-group validated row">
                  <div class="form-group col-lg-6 mb-3">
                      <label for="first_name">First name</label>
                      <input type="text" id="first_name" v-model="form.first_name" class="form-control border-gray-200" placeholder="First name">
                      <span class="text-danger" v-if="form.errors.first_name">{{ form.errors.first_name }}</span>
                 </div>

                 <div class="form-group col-lg-6 mb-3">
                      <label for="last_name">Last name</label>
                      <input type="text" id="last_name" v-model="form.last_name" class="form-control border-gray-200" placeholder="Last name">
                      <span class="text-danger" v-if="form.errors.last_name">{{ form.errors.last_name }}</span>
                 </div>

                 <div class="form-group col-lg-6 mb-3">
                      <label for="email">Email</label>
                      <input type="email" id="email" v-model="form.email" class="form-control border-gray-200" placeholder="Email">
                      <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
                 </div>
                 <div class="form-group col-lg-6 mb-3">
                     <label for="profile_photo">Profile Photo</label>
                     <!-- <file-upload @input="form.profile_photo = $event.target.files[0]" :imageurl="imageUrl" /> -->
                     <FilePond v-model="form.profile_photo" :myFile="(user?.profile_photo_path)? user?.profile_photo_url : null"/>
                      <span class="text-danger" v-if="form.errors.profile_photo">{{ form.errors.profile_photo }}</span>
                 </div>
               </div>

               <div class="kt-portlet__foot">
                  <div class="kt-form__actions">
                      <button type="submit" class="btn btn-primary kt-btn btn-sm kt-btn--icon button-fx me-2" :disabled="form.processing" :isLoading="form.processing">Submit</button>
                      <Link href="/admin/dashboard" class="btn btn-secondary kt-btn btn-sm kt-btn--icon button-fx" as="button" type="button">Cancel</Link>
                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>
  <div class="row mb-5">
      <div class="card">
              <admin-change-password/>
      </div>
  </div>

  </template>

  <script setup>
  import { onMounted, reactive,ref } from 'vue'
  import { useForm,router } from '@inertiajs/vue3'
  import FilePond from '@/components/FilePond.vue'
  import AdminChangePassword from './components/AdminChangePassword.vue'

const props = defineProps({
     errors:Object,
     user:Object
  })

  const form = useForm({
    first_name: props.user?.first_name || null,
    last_name: props.user?.last_name || null,
    email: props.user?.email || null,
    profile_photo: null,
  })

  onMounted(()=>{
       emit.emit('pageName', 'Profile ',[{title: "Dashboard", routeName:"admin.dashboard"},{title: "Profile", routeName:""}]);
  })

  function submit() {
      form.post(route('admin.admin-profile'),{preserveScroll: true});
  }

  </script>
   <style></style>
