<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Switch } from '@/components/ui/switch';
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
    globalvars: Array<{
        id: string;
        name: string;
        value: string;
        description: string;
        is_read_only: boolean;
        is_protected: boolean;
        user_id: number | null;
    }>;
}>();

const { globalvars } = props;
const loadingStates = ref<Record<string, boolean>>({});
const deletingStates = ref<Record<string, boolean>>({});
const tableKey = ref(0);

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Global Vars',
        href: '/ncms/globalvars',
    },
];

columns[3].cell = function ({ row }) {
    const url = `/ncms/globalvars/${row.original.id}/update/is_read_only`;
    return h(Switch, {
        modelValue: row.original.is_read_only,
        'onUpdate:modelValue': function (value: boolean) {
            row.original.is_read_only = value;
            loadingStates.value[row.original.id + '_is_read_only'] = true;
            axios
                .post(url, {
                    is_read_only: value,
                })
                .then((response) => {
                    if (response.status === 200) {
                        toast({
                            title: 'Success',
                            description: 'Global variable updated successfully.',
                        });

                        return;
                    }
                    toast({
                        title: 'Error',
                        variant: 'destructive',
                        description: 'Failed to update global variable.',
                    });
                })
                .catch((error) => {
                    toast({
                        title: 'Error',
                        variant: 'destructive',
                        description: 'Failed to update global variable. ' + error.message(),
                    });
                })
                .finally(() => {
                    loadingStates.value[row.original.id + '_is_read_only'] = false;
                });
        },
        'data-state': row.original.is_read_only ? 'checked' : 'unchecked',
        disabled: loadingStates.value[row.original.id + '_is_read_only'],
    });
};

columns[4].cell = function ({ row }) {
    const url = `/ncms/globalvars/${row.original.id}/update/is_protected`;
    return h(Switch, {
        modelValue: row.original.is_protected,
        'onUpdate:modelValue': function (value: boolean) {
            row.original.is_protected = value;
            loadingStates.value[row.original.id + '_is_protected'] = true;
            axios
                .post(url, {
                    is_protected: value,
                })
                .then((response) => {
                    if (response.status === 200) {
                        toast({
                            title: 'Success',
                            description: 'Global variable updated successfully.',
                        });

                        return;
                    }
                    toast({
                        title: 'Error',
                        variant: 'destructive',
                        description: 'Failed to update global variable.',
                    });
                })
                .catch((error) => {
                    toast({
                        title: 'Error',
                        variant: 'destructive',
                        description: 'Failed to update global variable. ' + error.message(),
                    });
                })
                .finally(() => {
                    loadingStates.value[row.original.id + '_is_protected'] = false;
                });
        },
        'data-state': row.original.is_protected ? 'checked' : 'unchecked',
        disabled: loadingStates.value[row.original.id + '_is_protected'],
    });
};

columns[5].cell = function ({ row }) {
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
    const index = globalvars.findIndex((item) => item.id === id);
    deletingStates.value[index] = true;

    axios
        .delete(url)
        .then((response) => {
            if (index !== -1) {
                globalvars.splice(index, 1);
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
    <Head title="Global Vars" />

    <Toaster position="top-right" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-end">
                <Button variant="default" @click="router.get('/ncms/globalvars/create')"> Create Global Var </Button>
            </div>
            <DataTable :key="tableKey" :columns="columns" :data="globalvars" :deletingStates="deletingStates" />
        </div>
    </AppLayout>
</template>
