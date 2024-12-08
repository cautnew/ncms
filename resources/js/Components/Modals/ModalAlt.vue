<script setup lang="ts">
import { ref } from 'vue';

const dialog = ref<HTMLDialogElement>();
const visible = ref(false);

const props = defineProps({
  formClass: {
    type: String,
    default: "p-6",
  },
  closeOnBackdrop: {
    type: Boolean,
    default: true,
  },
});

const showDialog = (): void => {
  visible.value = true;
  dialog.value.showModal();
};

const closeDialog = (): void => {
  visible.value = false;
  dialog.value.close();
};

const eventClickOverDialog = (event: Event): void => {
  if (props.closeOnBackdrop && event.currentTarget === event.target) {
    closeDialog();
  }
};

defineExpose({
  show: showDialog,
  close: closeDialog,
  visible,
});
</script>

<template>
  <dialog class="bg-white shadow-md rounded-md" @click="eventClickOverDialog" ref="dialog">
    <form v-if="visible" method="dialog" :class="{[props.formClass]: props.formClass}">
      <slot />
    </form>
  </dialog>
</template>