<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { toast } from '@/components/ui/toast/use-toast';
import { router } from '@inertiajs/vue3';
import { MoreHorizontal } from 'lucide-vue-next';
import { defineEmits } from 'vue';

const props = defineProps<{
    globalvar: {
        id: string;
        name: string;
        value: string;
    };
}>();

const emit = defineEmits(['delete-row']);

function alertMessage() {
    emit('delete-row', props.globalvar.id);
}

function copy(value: string) {
    navigator.clipboard.writeText(value);
    toast({
        title: 'Copied to clipboard',
        description: 'Copied to clipboard',
    });
}
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="h-8 w-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="h-4 w-4" />
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>
            <DropdownMenuItem @click="copy(globalvar.name)"> Copy variable name </DropdownMenuItem>
            <DropdownMenuItem @click="copy(globalvar.value)"> Copy variable value </DropdownMenuItem>
            <DropdownMenuSeparator />
            <DropdownMenuItem @click="router.get(`/ncms/globalvars/${props.globalvar.id}/edit`)">Edit</DropdownMenuItem>
            <DropdownMenuItem @click="alertMessage" class="text-red-500">Delete</DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
