import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { DynamicField, type FieldConfig } from '@/components/form/DynamicField';

export default function Edit() {
    const { page_type, fields } = usePage().props as unknown as { page_type: any, fields: FieldConfig[] };

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Page Types', href: '/page-types' },
        { title: page_type.name, href: `/page-types/${page_type.id}` },
        { title: 'Edit', href: `/page-types/${page_type.id}/edit` },
    ];

    const initialFormState = fields ? fields.reduce((acc, field) => {
        acc[field.name] = page_type[field.name] ?? '';
        return acc;
    }, {} as Record<string, any>) : {};

    const { data, setData, put, processing } = useForm(initialFormState);

    const handleFieldChange = (name: string, value: any) => {
        setData(name, value);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(`/page-types/${page_type.id}`, {
            onSuccess: () => alert('Page Type updated successfully.'),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit Page Type: ${page_type.name}`} />
            <div className="flex w-full max-w-3xl flex-1 flex-col gap-6 p-4">
                <div>
                    <h2 className="text-xl font-semibold">Edit Page Type</h2>
                    <p className="text-sm text-muted-foreground">Update the structure details for {page_type.name}.</p>
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

                    <div className="flex justify-end gap-2">
                        <Button type="button" variant="outline" onClick={() => window.history.back()}>Cancel</Button>
                        <Button type="submit" disabled={processing}>Save Changes</Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
