<template>
     <div class="card">
          <div class="card-header d-flex justify-content-between">
            <div class="card-title mb-0">
              <h5 class="mb-0">{{ props?.faq ? 'Update' : 'Create' }} FAQ</h5>
            </div>
          </div>
          <div class="card-body">
               <form @submit.prevent="submit()">
                    <div class="form-group validated row">
                         <div class="form-group mb-3">
                              <label for="question">Question</label>
                              <input type="text" id="question" v-model="store.form.question"
                                   class="form-control border-gray-200" placeholder="Question">
                              <span class="text-danger" v-if="store.form.errors?.question">{{ store.form.errors?.question[0]
                                   }}</span>
                         </div>
                         <div class="form-group validated row mb-3">
                              <label for="answer">Answer</label>
                              <Editor v-model="store.form.answer" editorStyle="height: 320px" />

                              <span class="text-danger" v-if="store.form.errors?.answer">{{ store.form.errors?.answer[0] }}</span>
                         </div>
                    </div>
                    <div class="kt-portlet__foot">
                         <div class="kt-form__actions">
                              <Button type="submit" class="btn btn-primary kt-btn kt-btn--icon button-fx me-2"
                                   :loading="store.form.processing" label="Submit" />
                              <Link :href="route('admin.faq.list')" class="btn btn-outline-primary waves-effect"
                                   as="button" type="button">Cancel</Link>
                         </div>
                    </div>
               </form>
          </div>
     </div>
</template>

<script setup>
import { onMounted, reactive, ref, nextTick } from 'vue'
import { useFaqStore } from '@/Stores/FaqStore';


const store = useFaqStore();
const props = defineProps({
     faq: Object
});



onMounted(() => {
     nextTick(() => {
          const pageName = props?.faq ? 'Update' : 'Create';
          const breadcrumbs = [
               { title: 'FAQ', routeName: "admin.faq.list" },
               {
                    title: pageName,
                    routeName: props?.faq ? "admin.faq.list" : "admin.faq.create"
               }
          ];

          emit.emit('pageName', pageName, breadcrumbs);
     });
     store.faq = props.faq ? props.faq : null;
     store.setFormData();
});

function submit() {
     if (props.faq) {
          store.updateFaq(props.faq.id);
     }
     else {
          store.createFaq();
     }
};

</script>