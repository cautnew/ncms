<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

  <Head title="Gerenciar" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gerenciar</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <Card>
          <h3>Manage your environment</h3>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
