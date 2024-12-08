<script setup>
import CurrencyBrl from '@/Components/FormatNumber/CurrencyBrl.vue';
import Fund from '../ClassFund.js';

const props = defineProps({
  fund: {
    type: Fund,
    default: undefined
  }
});

const classesForCard = ['rounded-lg', 'border-2', 'px-3', 'py-2', 'sm:w-100'];
const classesForCurrencyParagraph = ['font-bold', 'text-xl'];

if (props.fund !== undefined) {
  if (props.fund.balance > 0.0) {
    classesForCard.push('border-green-400', 'bg-green-50');
    classesForCurrencyParagraph.push('text-green-600');
  } else if (props.fund.balance < 0.0) {
    classesForCard.push('border-red-400', 'bg-red-50');
    classesForCurrencyParagraph.push('text-red-600');
  }
} else {
  classesForCard.push('border-gray-300', 'bg-gray-50');
}
</script>
<template>
  <div :class="classesForCard.join(' ')">
    <p v-if="props.fund" class="font-semibold text-lg">{{ props.fund.name }}</p>
    <p :class="classesForCurrencyParagraph.join(' ')">
      <CurrencyBrl v-if="props.fund" :value="props.fund.balance" />
      <slot />
    </p>
  </div>
</template>