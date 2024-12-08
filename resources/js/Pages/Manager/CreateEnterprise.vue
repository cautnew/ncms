<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/Buttons/PrimaryButton.vue";
import DangerButton from "@/Components/Buttons/DangerButton.vue";
import InputError from "@/Components/Inputs/InputError.vue";
import InputLabel from "@/Components/Inputs/InputLabel.vue";
import TextInput from "@/Components/Inputs/TextInput.vue";
import TextArea from '@/Components/Inputs/TextArea.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faChevronCircleLeft } from '@fortawesome/free-solid-svg-icons';

const form = useForm({
  name: '',
  description: '',
  email: '',
  website: '',
});
</script>

<template>

  <Head title="Create Enterprise" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Enterprise</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <Card>
          <header>
            <h3 class="font-semibold text-lg text-gray-800 leading-tight">Create your enterprise in the system.</h3>
            <p class="mt-3">Fill the form below to create a new enterprise. Later you can add some filials.</p>
          </header>

          <div class="flex justify-end">
            <Link :href="route('ncms.manager.enterprises')">
              <DangerButton><FontAwesomeIcon class="me-2" :icon="faChevronCircleLeft" />Return</DangerButton>
            </Link>
          </div>

          <form @submit.prevent="form.post('/ncms/manager/enterprises/create')" class="mt-6 space-y-6">
            <div>
              <InputLabel for="name" value="Name" />

              <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                modelValue="" />

              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
              <InputLabel for="description" value="Description" />

              <TextArea id="description" class="mt-1 block w-full" rows="2" v-model="form.description" modelValue="" />

              <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div>
              <InputLabel for="email" value="E-mail" />

              <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" modelValue="" />

              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
              <InputLabel for="website" value="Website" />

              <TextInput id="website" type="url" class="mt-1 block w-full" v-model="form.website" modelValue="" />

              <InputError class="mt-2" :message="form.errors.website" />
            </div>

            <div class="flex items-center gap-4">
              <PrimaryButton :disabled="form.processing">Create</PrimaryButton>

              <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                  Created.
                </p>
              </Transition>
            </div>
          </form>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
