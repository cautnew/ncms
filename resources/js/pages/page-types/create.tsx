import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { DynamicField, type FieldConfig } from '@/components/form/DynamicField';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Page Types',
        href: '/page-types',
    },
    {
        title: 'Create',
        href: '/page-types/create',
    },
];

export default function Create() {
    const { fields } = usePage().props as unknown as { fields: FieldConfig[] };

    const initialFormState = fields ? fields.reduce((acc, field) => {
        acc[field.name] = '';
        return acc;
    }, {} as Record<string, any>) : {};

    const { data, setData, post, processing } = useForm(initialFormState);

    const handleFieldChange = (name: string, value: any) => {
        setData(name, value);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/page-types', {
            onSuccess: () => alert('Page Type created successfully.'),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create a new page type" />
            <div className="flex w-full max-w-3xl flex-1 flex-col gap-6 p-4">
                <div>
                    <h2 className="text-xl font-semibold">Create a new page type</h2>
                    <p className="text-sm text-muted-foreground">Define a structure for your pages.</p>
                </div>

                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    {fields && fields.map((field) => (
                        <DynamicField
                            key={field.name}
                            field={field}
                            formData={data}
                            onChange={handleFieldChange}
                        />
                    ))}

                    <div className="flex justify-end">
                        <Button type="submit" disabled={processing}>Create Page Type</Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
