<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RegisterForm from './Partials/RegisterForm.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Ponto" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Ponto</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <RegisterForm />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
