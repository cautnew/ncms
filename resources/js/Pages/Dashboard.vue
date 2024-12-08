<script setup>
import { computed, ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faHouse } from '@fortawesome/free-solid-svg-icons';
import PrimaryButton from '@/Components/Buttons/PrimaryButton.vue';
import BodySimpleCard from '@/Components/BodySimpleCard.vue';

const pageTitle = "Dashboard";
const page = usePage();
const pageProps = page.props;
const person = computed(() => pageProps.auth.person);

const props = defineProps({
  auth: {
    person: {
      name: {
        type: String
    }
    },
    isJustLoggedIn: {type: Boolean},
  }
});
</script>

<template>
  <Head :title="pageTitle" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ pageTitle }}</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <BodySimpleCard class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <p v-if="props.auth.isJustLoggedIn || false" v-bind:class="{'text-green-500': true, 'text-red-500': false}">Bem vindo ao NCMS, {{ person.name }}!!!</p>
          <p v-else><FontAwesomeIcon :icon="faHouse" /></p>
        </BodySimpleCard>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
