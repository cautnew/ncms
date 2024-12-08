<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import BodySimpleCard from '@/Components/BodySimpleCard.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

const pageTitle = "Profile";

defineProps({
  mustVerifyEmail: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});
</script>

<template>
  <Head :title="pageTitle" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ pageTitle }}</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <BodySimpleCard>
          <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
          />
        </BodySimpleCard>

        <BodySimpleCard>
          <UpdatePasswordForm class="max-w-xl" />
        </BodySimpleCard>

        <BodySimpleCard>
          <DeleteUserForm class="max-w-xl" />
        </BodySimpleCard>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
