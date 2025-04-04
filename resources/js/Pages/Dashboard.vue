<script setup>
import NCMSLayout from "@/Layouts/NCMSLayout.vue";
import { FontAwesomeIcon as fa } from "@fortawesome/vue-fontawesome";
import { faSpinner } from "@fortawesome/free-solid-svg-icons";
import { Head } from "@inertiajs/vue3";
import Card from "@/components/ui/card/Card.vue";
import CardHeader from "@/components/ui/card/CardHeader.vue";
import CardContent from "@/components/ui/card/CardContent.vue";
</script>

<template>
    <Head title="Dashboard" />

    <NCMSLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>
        <template #breadcrumb>
            <BreadcrumbItem>
                <BreadcrumbPage>Dashboard</BreadcrumbPage>
            </BreadcrumbItem>
        </template>

        <Card>
            <CardHeader>
                <h3 class="text-lg font-semibold leading-tight text-gray-800">
                    You're logged in!
                    <fa spin-pulse :icon="faSpinner" />
                </h3>
            </CardHeader>
            <CardContent>
                <p class="text-gray-600">
                    This is your dashboard where you can manage your
                    application.
                </p>
                <p class="text-gray-600">
                    Use the sidebar to navigate through different sections.
                </p>
                <p class="text-gray-600">
                    If you have any questions, feel free to reach out to
                    support.
                </p>
            </CardContent>
        </Card>
    </NCMSLayout>
</template>
