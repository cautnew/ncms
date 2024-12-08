<script setup lang="ts">
import { ref } from "vue";
import InputError from "@/Components/Inputs/InputError.vue";
import InputLabel from "@/Components/Inputs/InputLabel.vue";
import TextInput from "@/Components/Inputs/TextInput.vue";
import BodySimpleCard from "@/Components/BodySimpleCard.vue";
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import GeoLocation from "./GeoLocation.vue";
import PhotoRecognition from "./PhotoRecognition.vue";

const geoLocator = ref(null);
const camera = ref<typeof PhotoRecognition | null>(null);
const formPhoto = ref<HTMLInputElement | null>(null);
const formLatitude = ref<HTMLInputElement | null>(null);
const formLongitude = ref<HTMLInputElement | null>(null);

const completeCollectGeolocation = (posInfo: any) => {
  if (formLatitude.value === null || formLongitude.value === null) {
    return;
  }

  formLatitude.value.value = posInfo.latitude;
  formLongitude.value.value = posInfo.longitude;
};

const errorCollectGeolocation = (error: any) => {
  console.log("error", error);
};

const form = useForm({
  photo: "",
  latitude: "",
  longitude: "",
});

const photoTaken = (photo: any) => {
  if (formPhoto.value === null) {
    return;
  }

  formPhoto.value.value = photo.image_data_url;
};

const submitForm = async () => {
  if (camera.value === null) {
    return;
  }

  await camera.value.camera.takePhoto();
  form.post(route("ncms.ponto.register"));
};
</script>
<template>
  <BodySimpleCard>
    <form @submit.prevent="submitForm" class="space-y-6">
      <GeoLocation ref="geoLocator" @load:geoposition="completeCollectGeolocation" @error:geoposition="errorCollectGeolocation" />
      <div class="sm:w-full lg:w-50">
        <PhotoRecognition ref="camera" @photoTaken="photoTaken" />
      </div>
      <div>
        <PrimaryButton class="w-full justify-center" :disabled="form.processing">Registrar</PrimaryButton>
      </div>
      <input type="hidden" ref="formPhoto" name="photo" />
      <input type="hidden" ref="formLatitude" name="latitude" />
      <input type="hidden" ref="formLongitude" name="longitude" />
    </form>
  </BodySimpleCard>
</template>
