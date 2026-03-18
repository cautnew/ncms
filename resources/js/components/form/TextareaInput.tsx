import React from 'react';
import { Label } from '@/components/ui/label';

/**
 * TextareaInput Component
 * 
 * A textarea component that allows multi-line text input up to a specified maximum number of characters.
 * 
 * @param {string} name - The name of the textarea field.
 * @param {string} label - The label displayed above the textarea field.
 * @param {string} value - The current value of the textarea field.
 * @param {number} maxLength - The maximum number of characters allowed (default is 270).
 * @param {function} onChange - Callback function triggered when the textarea value changes.
 */
interface TextareaInputProps {
    name: string;
    label: string;
    value: string;
    maxLength?: number;
    onChange: (value: string) => void;
}

export function TextareaInput({
    name,
    label,
    value,
    maxLength = 270,
    onChange,
}: TextareaInputProps) {
    const handleInputChange = (e: React.ChangeEvent<HTMLTextAreaElement>) => {
        const newValue = e.target.value;
        if (newValue.length <= maxLength) {
            onChange(newValue);
        }
    };

    return (
        <div className="flex flex-col gap-2">
            <Label htmlFor={name}>{label}</Label>
            <textarea
                id={name}
                name={name}
                value={value}
                onChange={handleInputChange}
                rows={4}
                className="flex min-h-[80px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-[3px] focus-visible:ring-ring/50 focus-visible:border-ring disabled:cursor-not-allowed disabled:opacity-50"
                placeholder="Enter page description..."
            />
            <div className="flex justify-end text-xs text-muted-foreground">
                <span>
                    {value.length}/{maxLength} characters
                </span>
            </div>
        </div>
    );
}
