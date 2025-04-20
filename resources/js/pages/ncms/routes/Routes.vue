<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { h, ref } from 'vue';
import columns from './data-table/columns';
import DropdownAction from './data-table/data-table-dropdown.vue';
import DataTable from './data-table/data-table.vue';

import AppLayout from '@/layouts/AppLayout.vue';

const { toast } = useToast();

const props = defineProps<{
    routes: Array<{
        id: string;
        name: string;
        value: string;
        description: string;
        is_read_only: boolean;
        is_protected: boolean;
        user_id: number | null;
    }>;
}>();

const { routes } = props;
const loadingStates = ref<Record<string, boolean>>({});
const deletingStates = ref<Record<string, boolean>>({});
const tableKey = ref(0);

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Routes',
        href: '/ncms/routes',
    },
];

columns[3].cell = function ({ row }) {
    return h(DropdownAction, {
        globalvar: {
            id: row.original.id,
            name: row.original.name,
            value: row.original.value,
        },
        onDeleteRow: (id: string) => {
            handleDeleteRow(id);
        },
    });
};

function handleDeleteRow(id: string) {
    const url = `/ncms/globalvars/${id}`;
    const index = routes.findIndex((item) => item.id === id);
    deletingStates.value[index] = true;

    axios
        .delete(url)
        .then((response) => {
            if (index !== -1) {
                routes.splice(index, 1);
                tableKey.value += 1;
            }
            if (response.status === 200) {
                toast({
                    title: 'Success',
                    description: 'Global variable deleted successfully.',
                });

                return;
            }
            toast({
                title: 'Error',
                variant: 'destructive',
                description: 'Failed to delete global variable.',
            });
        })
        .catch((error) => {
            toast({
                title: 'Error',
                variant: 'destructive',
                description: 'Failed to delete global variable. ' + error.message(),
            });
        })
        .finally(function () {
            // Define o estado de exclusão como false
            deletingStates.value[index] = false;
        });
}
</script>

<template>
    <Head title="Routes" />

    <Toaster position="top-right" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-end">
                <Button variant="default" @click="router.get('/ncms/route/create')"> Create Global Var </Button>
            </div>
            <DataTable :key="tableKey" :columns="columns" :data="routes" :deletingStates="deletingStates" />
        </div>
    </AppLayout>
</template>
