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
import { faChevronCircleLeft } from '@fortawesome/free-solid-svg-icons';

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

  <Head title="Edit Enterprise" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Enterprise</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <Card>
          <header>
            <h3 class="font-semibold text-lg text-gray-800 leading-tight">Edit your enterprise in the system.</h3>
            <p class="mt-3">Fill the form below to edit a new enterprise.</p>
          </header>

          <div class="flex justify-end">
            <Link :href="route('ncms.manager.enterprises')">
              <DangerButton><FontAwesomeIcon class="me-2" :icon="faChevronCircleLeft" />Return</DangerButton>
            </Link>
          </div>

          <form @submit.prevent="form.post(`/ncms/manager/enterprises/${enterprise.id}/edit`)" class="mt-6 space-y-6">
            <div>
              <InputLabel for="name" value="Name" />

              <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" modelValue="form.name" required autofocus />

              <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
              <InputLabel for="description" value="Description" />

              <TextArea id="description" class="mt-1 block w-full" rows="2" v-model="form.description" modelValue="form.description" />

              <InputError class="mt-2" :message="form.errors.description" />
            </div>

            <div>
              <InputLabel for="email" value="E-mail" />

              <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" modelValue="form.email" />

              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
              <InputLabel for="website" value="website" />

              <TextInput id="website" type="url" class="mt-1 block w-full" v-model="form.website" modelValue="form.website" />

              <InputError class="mt-2" :message="form.errors.website" />
            </div>

            <div class="flex items-center gap-4">
              <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

              <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                  Saved.
                </p>
              </Transition>
            </div>
          </form>
        </Card>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
