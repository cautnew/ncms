<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import LinkButton from '@/Components/Buttons/LinkButton.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import BodySimpleCard from '@/Components/BodySimpleCard.vue';

const pageTitle = "Add Expenses";

const form = useForm({
  name: '',
  value: '',
  date: '',
  category: '',
  description: ''
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
          <div class="flex justify-end mb-2">
            <LinkButton class="mt-3" href="#"><FontAwesomeIcon :icon="faPlus" class="me-2"/>-</LinkButton>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
            <form @submit.prevent="form.patch(route('ncms.financeiro.addexpenses'))">
              <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
              </div>
            </form>
          </div>
        </BodySimpleCard>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
