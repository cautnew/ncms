<script setup>
import { defineProps } from 'vue';
import LogoUrl from "@/Components/LogoURL";

const props = defineProps({
  logoId: {
    type: Number,
    default: null
  }
});
</script>
<template>
  <img :src="LogoUrl(props.logoId)" />
</template>
