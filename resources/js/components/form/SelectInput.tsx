import React from 'react';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

interface SelectOption {
    value: string;
    label: string;
}

interface SelectInputProps {
    name: string;
    label: string;
    value: string;
    options: SelectOption[];
    onChange: (value: string) => void;
}

export function SelectInput({
    name,
    label,
    value,
    options,
    onChange,
}: SelectInputProps) {
    return (
        <div className="flex flex-col gap-2">
            <Label htmlFor={name}>{label}</Label>
            <Select value={value} onValueChange={onChange}>
                <SelectTrigger id={name} className="w-full">
                    <SelectValue placeholder="Select an option..." />
                </SelectTrigger>
                <SelectContent>
                    {options.map((option) => (
                        <SelectItem key={option.value} value={option.value}>
                            {option.label}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
        </div>
    );
}
