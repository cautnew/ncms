<script setup lang="ts">
import NCMSLayout from "@/Layouts/NCMSLayout.vue";
import { FontAwesomeIcon as fa } from "@fortawesome//vue-fontawesome";
import { faSpinner } from "@fortawesome//free-solid-svg-icons";
import { BreadcrumbItem, BreadcrumbPage } from "@/components/ui/breadcrumb";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    name: {
        type: Object,
        required: false,
    },
    userData: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Test Sidebar" />
    <NCMSLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Test Sidebar
            </h2>
        </template>
        <template #breadcrumb>
            <BreadcrumbItem>
                <BreadcrumbPage>Home</BreadcrumbPage>
            </BreadcrumbItem>
        </template>
        <h3 class="text-lg font-semibold leading-tight text-gray-800">
            <span>{{ props.name }}</span>
            <fa class="ms-2" spin-pulse :icon="faSpinner" />
            <p>{{ props.userData }}</p>
        </h3>
    </NCMSLayout>
</template>
