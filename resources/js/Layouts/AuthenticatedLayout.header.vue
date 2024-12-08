<script setup>
import { ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faEllipsisVertical, faMinus } from "@fortawesome/free-solid-svg-icons";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import menuOptions from "@/Layouts/AuthenticatedLayout.menuoptions";

const showingSubNavigationDropdown = ref(false);
const currentSection = menuOptions.getCurrentSection(route().current())[0] || [];
</script>

<template>
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-4 px-6">
      <div class="flex justify-start items-center">
        <button @click="showingSubNavigationDropdown = !showingSubNavigationDropdown"
          v-if="currentSection.submenu?.length" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out me-2">
          <FontAwesomeIcon class="h-5 w-5" :icon="showingSubNavigationDropdown ? faMinus : faEllipsisVertical" />
        </button>
        <slot name="header" v-if="$slots.header" />
      </div>
      <div :class="{
        block: showingSubNavigationDropdown,
        hidden: !showingSubNavigationDropdown,
      }">
        <div class="py-2 space-y-1">
          <ResponsiveNavLink v-for="section in currentSection.submenu" :key="section" :href="route(section.route)" :active="route().current(section.route)">
            {{ section.name }}
          </ResponsiveNavLink>
        </div>
      </div>
    </div>
  </header>
</template>
