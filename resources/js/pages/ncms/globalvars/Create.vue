<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import { Toaster } from '@/components/ui/toast';
import { useToast } from '@/components/ui/toast/use-toast';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';
import { useForm } from 'vee-validate';

import AppLayout from '@/layouts/AppLayout.vue';
import { toTypedSchema } from '@vee-validate/zod';

import { h } from 'vue';

import * as z from 'zod';

const { toast } = useToast();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Global Vars',
        href: '/ncms/globalvars',
    },
    {
        title: 'Create',
        href: `/ncms/globalvars/create`,
    },
];

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(50),
        value: z.string().max(150),
        description: z.string().optional(),
        is_read_only: z.boolean().default(false).optional(),
        is_protected: z.boolean().default(false).optional(),
    }),
);

const { isFieldDirty, handleSubmit, setErrors } = useForm({
    validationSchema: formSchema,
});

const onSubmit = handleSubmit((values, actions) => {
    axios
        .post('/ncms/globalvars', values)
        .then(function (response) {
            if (response.status === 200) {
                toast({
                    title: 'Success',
                    description: 'Global variable created successfully.',
                });

                router.get('/ncms/globalvars');
                return;
            }

            toast({
                title: 'Error',
                description: 'Failed to create global variable.',
            });
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                setErrors(error.response.data.errors);
            } else {
                toast({
                    title: 'Error',
                    description: 'An unexpected error occurred.',
                });
            }
        });
    toast({
        title: 'You submitted the following values:',
        description: h(
            'pre',
            { class: 'mt-2 w-[340px] rounded-md bg-slate-950 p-4' },
            h('code', { class: 'text-white' }, JSON.stringify(values, null, 2)),
        ),
    });
});
</script>

<template>
    <Head title="Create - Global Vars" />

    <Toaster position="top-right" />
    <AppLayout :breadcrumbs="breadcrumbItems">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 sm:w-full lg:max-w-7xl">
            <form class="w-2/3 sm:space-y-2 md:space-y-2 lg:space-y-3" @submit="onSubmit">
                <FormField v-slot="{ componentField }" name="name" :validate-on-blur="!isFieldDirty">
                    <FormItem>
                        <FormLabel>Variable name</FormLabel>
                        <FormControl>
                            <Input type="text" placeholder="Variable name" v-bind="componentField" />
                        </FormControl>
                        <FormDescription> This is the variable name which you can use to find it. </FormDescription>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="value" :validate-on-blur="!isFieldDirty">
                    <FormItem>
                        <FormLabel>Value</FormLabel>
                        <FormControl>
                            <Input type="text" placeholder="Value" v-bind="componentField" />
                        </FormControl>
                        <FormDescription> This is the value for the variable. </FormDescription>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ componentField }" name="description" :validate-on-blur="!isFieldDirty">
                    <FormItem>
                        <FormLabel>Description</FormLabel>
                        <FormControl>
                            <Textarea placeholder="Description" v-bind="componentField" />
                        </FormControl>
                        <FormDescription> This is your public display name. </FormDescription>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ handleChange, value }" name="is_read_only" :validate-on-blur="!isFieldDirty">
                    <FormItem class="mb-2 flex space-x-2 space-y-0">
                        <FormControl>
                            <Switch :model-value="value" @update:model-value="handleChange" />
                        </FormControl>
                        <FormLabel class="content-center">Read Only</FormLabel>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ handleChange, value }" name="is_protected" class="mb-2" :validate-on-blur="!isFieldDirty">
                    <FormItem class="mb-2 flex space-x-2 space-y-0">
                        <FormControl>
                            <Switch :model-value="value" @update:model-value="handleChange" />
                        </FormControl>
                        <FormLabel class="content-center">Protected</FormLabel>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <div class="flex justify-end">
                    <Button type="submit"> Submit </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
