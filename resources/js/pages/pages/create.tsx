import React, { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { DynamicField, type FieldConfig } from '@/components/form/DynamicField';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: '/pages',
    },
    {
        title: 'Create',
        href: '/pages/create',
    },
];

export default function Create() {
    const { fields } = usePage().props as unknown as { fields: FieldConfig[] };
    
    // Manage form state
    const [formData, setFormData] = useState<Record<string, any>>({});
    
    const handleFieldChange = (name: string, value: any) => {
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        console.log('Form data to submit:', formData);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create a new page" />
            <div className="mx-auto flex w-full max-w-3xl flex-1 flex-col gap-6 p-4">
                <div>
                    <h2 className="text-xl font-semibold">Create a new page</h2>
                    <p className="text-sm text-muted-foreground">Fill in the details below to create a new page.</p>
                </div>
                
                <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                    {fields && fields.map((field) => (
                        <DynamicField 
                            key={field.name} 
                            field={field} 
                            formData={formData} 
                            onChange={handleFieldChange} 
                        />
                    ))}
                    
                    <div className="flex justify-end">
                        <Button type="submit">Create Page</Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
