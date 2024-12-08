<script setup lang="ts">
import {WebCam} from "vue-camera-lib";
import "@/shared/tools/FaceAPI/face-api.min";
import { ref } from "vue";
import { faPhotoFilm } from "@fortawesome/free-solid-svg-icons";

const emit = defineEmits(['photoTaken']);
const camera = ref<typeof WebCam>();

const photoTakenEvent = ({ blob, image_data_url }: { blob: Blob, image_data_url: string }) => {
  emit('photoTaken', { blob, image_data_url })
};

defineExpose({
  camera
});
</script>

<template>
  <!--<WebCamUI :fullscreenButton="{display: false}" :constraints="{
    video: {
      width: {ideal: 800},
      height: {ideal: 800}
    },
    facingMode: 'environment'
  }" />-->
  <WebCam ref="camera" :constraints="{
    video: {
      width: {ideal: 800},
      height: {ideal: 800}
    },
    facingMode: 'environment'
  }" @photoTaken="photoTakenEvent" />
</template>
