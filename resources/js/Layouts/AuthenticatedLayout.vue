<script setup>
import { Head } from "@inertiajs/vue3";
import LogoUrl from "@/Components/LogoURL";
import LayoutNav from "@/Layouts/AuthenticatedLayout.nav.vue";
import LayoutHeader from "@/Layouts/AuthenticatedLayout.header.vue";
import LayoutFooter from "@/Layouts/AuthenticatedLayout.footer.vue";
</script>

<template>
  <Head>
    <link rel="icon" type="image/svg+xml" :href="LogoUrl(1)" />
  </Head>
  <div class="min-h-screen bg-gray-100">
    <LayoutNav />

    <LayoutHeader>
      <template #header>
        <slot name="header" v-if="$slots.header" />
      </template>
    </LayoutHeader>

    <main>
      <slot />
    </main>

    <LayoutFooter />
  </div>
</template>
