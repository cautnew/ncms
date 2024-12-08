<template>
  <slot />
</template>
<script setup lang="ts">
const props = defineProps<{
  loadOnPageLoad?: Boolean;
}>();
const emit = defineEmits(['load:geoposition', 'error:geoposition']);

/** Model to collect the geolocation in the parent component
 * const getCurrentGeoLocation = (position) => {
 *   console.log({
 *     icon: "info",
 *     heading: "Geolocalização",
 *     text: `Geolocalização obtida...`
 *   });
 *
 *   geolocationLongitude.value = position.coords.longitude;
 *   geolocationLatitude.value = position.coords.latitude;
 * };
 */

/** Model to collect errors to get geolocation in the parent component
 * const errorGetCurrentGeoLocation = (position) => {
 *   if (position.code == 1) {
 *     console.log({
 *       icon: "error",
 *       heading: "Erro ao registrar a geolocalização",
 *       text: `Não foi possível obter sua localização. Por favor, autorize o acesso a sua geolocalização!`,
 *     });
 *   }
 * };
 */

const onCompleteGetCurrentGeoLocation = (position: any) => {
  emit('load:geoposition', {
    position,
    latitude: position.coords.latitude,
    longitude: position.coords.longitude
  });
};

const errorGetCurrentGeoLocation = (error: any) => {
  emit('error:geoposition', error);
};

const getCurrentGeoLocation = () => {
  navigator.geolocation.getCurrentPosition(onCompleteGetCurrentGeoLocation, errorGetCurrentGeoLocation);
};

window.addEventListener('load', function () {
  if (props.loadOnPageLoad || props.loadOnPageLoad === undefined) {
    getCurrentGeoLocation();
  }
});

defineExpose({
  getCurrentGeoLocation
});
</script>
