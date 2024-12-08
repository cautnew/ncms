<script setup lang="ts">
import { computed, ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faHouse } from '@fortawesome/free-solid-svg-icons';
import Modal from '@/Components/Modals/Modal.vue';
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import BodySimpleCard from '@/Components/BodySimpleCard.vue';

const page = usePage();
const pageProps = page.props;
const person = computed(() => pageProps.auth.person);

const modalPrincipal = ref<HTMLDialogElement>();

const props = defineProps<{
  auth: {
    person: {
      name: string;
    },
    isJustLoggedIn: boolean,
  };
}>();
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <BodySimpleCard class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <p v-if="props.auth.isJustLoggedIn || false" v-bind:class="{'text-green-500': true, 'text-red-500': false}">Bem vindo ao NCMS, {{ person.name }}!!!</p>
          <p v-else><FontAwesomeIcon :icon="faHouse" /></p>
        </BodySimpleCard>
      </div>
    </div>

    <Modal ref="modalPrincipal">
      <p>Oi</p>
      <PrimaryButton>Clique para fechar</PrimaryButton>
    </Modal>
  </AuthenticatedLayout>
</template>
