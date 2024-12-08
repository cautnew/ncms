<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import InputError from "@/Components/Inputs/InputError.vue";
import InputLabel from "@/Components/Inputs/InputLabel.vue";
import TextInput from "@/Components/Inputs/TextInput.vue";
import TextArea from '@/Components/Inputs/TextArea.vue';
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import DangerButton from '@/Components/Buttons/DangerButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faChevronCircleLeft, faTrash } from '@fortawesome/free-solid-svg-icons';

const page = usePage();
const props = page.props;
const enterprise = props.enterprise;
console.log(enterprise);
console.log(enterprise.id);

const form = useForm({
  name: enterprise.name,
  description: enterprise.description,
  email: enterprise.email,
  website: enterprise.website,
});
</script>

<template>

  <Head title="Confirm Delete Enterprise" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Confirm Delete Enterprise</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <Card>
          <header>
            <h3 class="font-semibold text-lg text-gray-800 leading-tight">Do ou confirm delete your enterprise in the system?</h3>
            <p class="mt-3">Fill the form below to edit a new enterprise.</p>
          </header>

          <form @submit.prevent="form.delete(`/ncms/manager/enterprises/${enterprise.id}/delete`)" class="flex justify-end">
            <Link :href="route('ncms.manager.enterprises')">
              <PrimaryButton><FontAwesomeIcon class="me-2" :icon="faChevronCircleLeft" />Return</PrimaryButton>
            </Link>
            <DangerButton class="ms-2" type="submit"><FontAwesomeIcon :icon="faTrash" class="me-2"/>Delete</DangerButton>
          </form>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
