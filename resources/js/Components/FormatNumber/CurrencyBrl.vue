<script setup>
import VNum from 'v3-num';

const props = defineProps({
  value: {
    default: 0.0
  },
});
</script>
<template>
  <VNum #="{ number }" locale="pt-br" minimumFractionDigits="2" numberStyle="currency" currency="BRL" :value="props.value">{{ number }}</VNum>
</template>