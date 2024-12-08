<script setup>
import { defineAsyncComponent, computed, ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Modal from '@/Components/Modals/Modal.vue';

const page = usePage();
const pageProps = page.props;

const components = {};
pageProps.componentImport.forEach((component) => {
  components[component.name] = defineAsyncComponent(() => import(component.from));
});
</script>

<template>
  <Head :title="pageProps.title" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ pageProps.title }}</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <component v-for="(content, contentIndex) in pageProps.content" :is="components[content.componentName] || content.componentName">
          {{ content.componentName }} - {{ content.content }}
        </component>

        <Modal :show="true">asdasd asd asdd</Modal>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
