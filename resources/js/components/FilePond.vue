<template>
    <div>
      <file-pond
              name="file"
              ref="pond"
              class-name="my-pond"
              :accepted-file-types="types"
              :files="myFile"
              @addfile="handleFilePondInit"
              @removefile="onFileRemove"
              image-preview-height=50
              label-idle="Drag and Drop your picture or <span class='filepond--label-action'>Browse</span>"
          />
    </div>
  </template>

  <script setup>
  import { onMounted, ref } from 'vue'
  import vueFilePond from 'vue-filepond';
  import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js';
  import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js';

  import 'filepond/dist/filepond.min.css';
  import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';


  const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview);

  // const myFile = ref();
  const pond = ref();
  defineProps({
      modelValue:Object,
      myFile:String,
      types:{
          type: String,
          default: 'image/png, image/jpeg, image/gif'
      }
  })
  const emit = defineEmits(['update:modelValue', 'removefile']);

  const handleFilePondInit = (error, file) => {
      if (error) {
          console.log('Oh no');
          return;
      }
       emit('update:modelValue', file.file)
  //   form.logo.push(file.file);
   }

   const onFileRemove = (error, file) => {
    emit('removefile');
      if (error) {
        console.error("Error removing file:", error);
        return;
      }
    }

//    emit('onremovefile');

  </script>
