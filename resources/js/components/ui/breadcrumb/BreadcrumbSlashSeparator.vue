<script setup>
import { cn } from '@/lib/utils';
import { Slash } from 'lucide-vue-next';

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <li
    role="presentation"
    aria-hidden="true"
    :class="cn('[&>svg]:size-3.5', props.class)"
  >
    <Slash />
    <slot />
  </li>
</template>
