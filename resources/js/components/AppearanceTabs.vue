<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { faMoon, faSun, faTv } from '@fortawesome//free-solid-svg-icons';
import { FontAwesomeIcon as fa } from '@fortawesome//vue-fontawesome';

interface Props {
    class?: string;
}

const { class: containerClass = '' } = defineProps<Props>();

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: faSun, label: 'Light' },
    { value: 'dark', Icon: faMoon, label: 'Dark' },
    { value: 'system', Icon: faTv, label: 'System' },
] as const;
</script>

<template>
    <div :class="['inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', containerClass]">
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 transition-colors',
                appearance === value
                    ? 'bg-white shadow-sm dark:bg-neutral-700 dark:text-neutral-100'
                    : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
            ]"
        >
            <!--<component :is="Icon" class="-ml-1 h-4 w-4" />-->
            <fa :icon="Icon" />
            <span class="ml-1.5 text-sm">{{ label }}</span>
        </button>
    </div>
</template>
