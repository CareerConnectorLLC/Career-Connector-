<template>
     <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
              <h5 class="mb-0">{{ props?.cms ? 'Update' : 'Create' }} CMS</h5>
            </div>
          </div>
          <div class="card-body">
               <form @submit.prevent="submit()">
                    <div class="form-group validated row">
                         <div class="form-group col-lg-6 mb-3">
                              <label for="title">Title</label>
                              <input type="text" id="title" v-model="form.title" class="form-control border-gray-200"
                                   placeholder="Title">
                              <span class="text-danger" v-if="form.errors?.title">{{ form.errors?.title }}</span>
                         </div>
                         <div class="form-group validated row mb-3">
                              <label for="title">Content</label>
                              <Editor v-model="form.text_content" editorStyle="height: 320px" />

                              <span class="text-danger" v-if="form.errors?.text_content">{{ form.errors?.text_content }}</span>
                         </div>
                         {{ metaData }}
                         <div class="form-group row mb-3">
                              <div class="col-lg-4">
                                   <label for="meta_title">Meta Title</label>
                                   <input type="text" id="meta_title" v-model="form.meta_title" class="form-control border-gray-200"
                                        placeholder="Meta Title">
                                   <span class="text-danger" v-if="form.errors?.meta_title">{{ form.errors?.meta_title }}</span>
                              </div>
                              <div class="col-lg-4">
                                   <label for="meta_description">Meta Description</label>
                                   <input type="text" id="meta_description" v-model="form.meta_description" class="form-control border-gray-200"
                                        placeholder="Meta Description">
                                   <span class="text-danger" v-if="form.errors?.meta_description">{{ form.errors?.meta_description }}</span>
                              </div>
                              <div class="col-lg-4">
                                   <label for="meta_keywords">Meta Keywords</label>
                                   <input type="text" id="meta_keywords" v-model="form.meta_keywords" class="form-control border-gray-200"
                                        placeholder="Meta Keywords">
                                   <span class="text-danger" v-if="form.errors?.meta_keywords">{{ form.errors?.meta_keywords }}</span>
                              </div>
                         </div>
                    </div>

                    <div class="kt-portlet__foot">
                         <div class="kt-form__actions">
                              <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2" label="Submit"/>
                              <Link :href="route('admin.cms.list')" class="btn btn-outline-primary waves-effect" as="button" type="button">Cancel</Link>
                         </div>
                    </div>
               </form>
          </div>
     </div>
</template>

<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue'
import { useForm,router } from '@inertiajs/vue3'
import { useToast } from "primevue/usetoast";


const toast = useToast();

const props = defineProps({
     cms:Object
});


onMounted(() => {
     nextTick(() => {
          const pageName = props?.cms ? 'Update' : 'Create';
          const breadcrumbs = [
               { title: 'CMS', routeName: "admin.cms.list" },
               {
                    title: pageName,
                    routeName: props?.cms ? "admin.cms.list" : "admin.cms.create"
               }
          ];

          emit.emit('pageName', pageName, breadcrumbs);
     });
});

const form = useForm({
     errors: {},
     title: props.cms?.title || null,
     text_content: props.cms?.text_content || null,
     meta_title: props.cms?.meta_title || null,
     meta_description: props.cms?.meta_description || null,
     meta_keywords: props.cms?.meta_keywords || null,
});

function submit() {
     if (props.cms) {
          // updateCMS(props.cms.id);
          form.post(route('admin.cms.update', props.cms?.id), { preserveState: true });
     }
     else {
          form.post(route('admin.cms.store'), { preserveState: true });
     }
};


</script>