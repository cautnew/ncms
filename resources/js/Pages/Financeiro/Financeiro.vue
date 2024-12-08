<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faPlus } from '@fortawesome/free-solid-svg-icons';
import LinkButton from '@/Components/Buttons/LinkButton.vue';
import FundCard from './Partials/FundCard.vue';
import Fund from './ClassFund.js';
import { Head, usePage } from '@inertiajs/vue3';
import BodySimpleCard from '@/Components/BodySimpleCard.vue';

const funds = usePage().props.funds;

const listOfFunds = funds.map((fund) => {
  return new Fund(fund.name, fund.balance);
});
</script>

<template>

  <Head title="Financeiro" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Financeiro Pessoal</h2>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <BodySimpleCard>
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">Saldo dos Fundos</h2>
          <div class="flex justify-end mb-2">
            <LinkButton class="mt-3" :href="route('ncms.manager.enterprises.create')"><FontAwesomeIcon :icon="faPlus" class="me-2"/>Add new expenses</LinkButton>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
            <FundCard v-for="fund in listOfFunds" :fund="fund" />
            <FundCard class="flex justify-center items-center"><FontAwesomeIcon :icon="faPlus" class="text-gray-400"/></FundCard>
          </div>
        </BodySimpleCard>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
