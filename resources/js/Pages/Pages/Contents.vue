<script setup>
import BodySimpleCard from '@/Components/BodySimpleCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Pages Contents" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight ms-2">Pages Contents</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <BodySimpleCard>
          <p>Pages content you have created.</p>
          <table class="data-table w-full">
            <thead>
              <tr>
                <th>Title</th>
                <th>Route</th>
                <th>Author</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr class="">
                <td>Home</td>
                <td>/home</td>
                <td>Anon</td>
                <td>
                  <a href="#">Edit</a>
                  <a href="#">Del</a>
                </td>
              </tr>
              <tr>
                <td>About</td>
                <td>/about</td>
                <td>Anon</td>
                <td>
                  <a href="#">Edit</a>
                  <a href="#">Del</a>
                </td>
              </tr>
            </tbody>
          </table>
        </BodySimpleCard>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
