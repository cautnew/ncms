<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCopyright } from '@fortawesome/free-regular-svg-icons';
import { faHeart } from '@fortawesome/free-solid-svg-icons';
</script>
<template>
  <footer class="bg-white border-t border-gray-100 shadow">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <div class="text-sm text-gray-500 text-center w-full">
          <p class="mb-2">Feito com <FontAwesomeIcon :icon="faHeart" class="text-red-600 mx-1" title="Amor" /> por CautNew</p>
          <p title="Todos os direitos reservados a CautNew"><FontAwesomeIcon :icon="faCopyright" title="Copyright" /> 2024</p>
        </div>
      </div>
    </div>
  </footer>
</template>
