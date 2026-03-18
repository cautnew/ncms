import React, { useState } from 'react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

/**
 * TextInput Component
 * 
 * A text input component that accepts up to a specified maximum number of characters.
 * It alerts the user when they reach a certain character count (e.g., warning them when they reach 80 chars out of 130).
 * 
 * @param {string} name - The name of the input field.
 * @param {string} label - The label displayed above the input field.
 * @param {string} value - The current value of the input field.
 * @param {number} maxLength - The maximum number of characters allowed (default is 130).
 * @param {number} alertAt - The character count at which to show a warning (default is 80).
 * @param {function} onChange - Callback function triggered when the input value changes.
 */
interface TextInputProps {
    name: string;
    label: string;
    value: string;
    maxLength?: number;
    alertAt?: number;
    onChange: (value: string) => void;
}

export function TextInput({
    name,
    label,
    value,
    maxLength = 130,
    alertAt = 80,
    onChange,
}: TextInputProps) {
    const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const newValue = e.target.value;
        if (newValue.length <= maxLength) {
            onChange(newValue);
        }
    };

    const isNearLimit = value.length >= alertAt;

    return (
        <div className="flex flex-col gap-2">
            <Label htmlFor={name}>{label}</Label>
            <Input
                id={name}
                name={name}
                type="text"
                value={value}
                onChange={handleInputChange}
                className={isNearLimit ? 'border-yellow-500 focus-visible:ring-yellow-500/50' : ''}
                placeholder="Enter page title..."
            />
            <div className="flex justify-end text-xs text-muted-foreground">
                <span className={isNearLimit ? 'text-yellow-600 dark:text-yellow-500 font-medium' : ''}>
                    {isNearLimit && `You have reached ${alertAt} characters. `}
                    {value.length}/{maxLength} characters
                </span>
            </div>
        </div>
    );
}
