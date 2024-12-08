<script setup>
import { Head } from '@inertiajs/vue3';
import Card from '@/Components/Card.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
  <Head title="Tools" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight ms-2">Tools</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <Card>
          asd
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>