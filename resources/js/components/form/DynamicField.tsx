import React from 'react';
import { TextInput } from '@/components/form/TextInput';
import { TextareaInput } from '@/components/form/TextareaInput';
import { ImageInput } from '@/components/form/ImageInput';
import { SelectInput } from '@/components/form/SelectInput';

// Define the shape of our field configuration
export type FieldConfig = {
    type: 'text' | 'textarea' | 'image' | 'select';
    name: string;
    label: string;
    rows?: number;
    maxLength?: number;
    alertAt?: number;
    label_description?: string;
    options?: { value: string; label: string }[];
};

interface DynamicFieldProps {
    field: FieldConfig;
    formData: Record<string, any>;
    onChange: (name: string, value: any) => void;
}

/**
 * DynamicField Component
 * 
 * A wrapper component that renders the appropriate input type based on the field configuration.
 * This abstracts the switch/case logic away from the page components.
 * 
 * @param {FieldConfig} field - The configuration object for the field
 * @param {Record<string, any>} formData - The current state containing all form data
 * @param {function} onChange - Callback function to update form state
 */
export function DynamicField({ field, formData, onChange }: DynamicFieldProps) {
    switch (field.type) {
        case 'text':
            return (
                <TextInput
                    key={field.name}
                    name={field.name}
                    label={field.label}
                    value={formData[field.name] || ''}
                    maxLength={field.maxLength}
                    alertAt={field.alertAt}
                    onChange={(val) => onChange(field.name, val)}
                />
            );
        case 'textarea':
            return (
                <TextareaInput
                    key={field.name}
                    name={field.name}
                    label={field.label}
                    label_description={field.label_description}
                    value={formData[field.name] || ''}
                    rows={field.rows}
                    maxLength={field.maxLength}
                    onChange={(val) => onChange(field.name, val)}
                />
            );
        case 'image':
            return (
                <ImageInput
                    key={field.name}
                    name={field.name}
                    label={field.label}
                    image={formData[`${field.name}_file`] || null}
                    description={formData[`${field.name}_desc`] || ''}
                    onImageChange={(file) => onChange(`${field.name}_file`, file)}
                    onDescriptionChange={(desc) => onChange(`${field.name}_desc`, desc)}
                />
            );
        case 'select':
            return (
                <SelectInput
                    key={field.name}
                    name={field.name}
                    label={field.label}
                    value={formData[field.name] || ''}
                    options={field.options || []}
                    onChange={(val) => onChange(field.name, val)}
                />
            );
        default:
            return null;
    }
}
