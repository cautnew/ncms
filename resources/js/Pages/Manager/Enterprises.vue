<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPlus, faTrash, faEdit } from '@fortawesome/free-solid-svg-icons';
import LinkButton from '@/Components/Buttons/LinkButton.vue';
import PrimaryLinkButton from '@/Components/Buttons/PrimaryLinkButton.vue';
import DangerLinkButton from '@/Components/Buttons/DangerLinkButton.vue';

const page = usePage();
const props = page.props;
const enterprises = props.enterprises;
</script>

<template>

  <Head title="Enterprises" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Enterprises</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <Card>
          <h3 class="font-semibold text-lg text-gray-800 leading-tight">Your enterprises list</h3>
          <div class="flex justify-end">
            <LinkButton class="mt-3" :href="route('ncms.manager.enterprises.create')"><FontAwesomeIcon :icon="faPlus" class="me-2"/>Create new enterprise</LinkButton>
          </div>
          <table v-if="enterprises.length > 0" class="mt-3 min-w-full text-left text-sm font-light text-surface data-table">
            <thead>
              <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="enterprise in enterprises">
                <td>{{ enterprise.name }}</td>
                <td>{{ enterprise.description }}</td>
                <td>
                  <PrimaryLinkButton :href="`enterprises/${enterprise.enterprise_id}/edit`" class="me-2" title="Edit"><FontAwesomeIcon :icon="faEdit"/></PrimaryLinkButton>
                  <DangerLinkButton :href="`enterprises/${enterprise.enterprise_id}/delete`" title="Delete"><FontAwesomeIcon :icon="faTrash"/></DangerLinkButton>
                </td>
              </tr>
            </tbody>
          </table>
          <div v-else class="bg-amber-100 p-2 mt-3 text-center rounded-md border-solid border-2 border-amber-200">
            <p>You don't have any enterprise yet.</p>
          </div>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
