import React, { useRef, useState } from 'react';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';

/**
 * ImageInput Component
 * 
 * A compound component that allows uploading an image file and providing a descriptive text (alt text) for it.
 * 
 * @param {string} name - The name of the image field (used as prefix for inputs).
 * @param {string} label - The label displayed above the image upload field.
 * @param {File | null} image - The current selected image file.
 * @param {string} description - The descriptive text for the image.
 * @param {function} onImageChange - Callback triggered when a new image is selected.
 * @param {function} onDescriptionChange - Callback triggered when the descriptive text changes.
 */
interface ImageInputProps {
    name: string;
    label: string;
    image: File | null;
    description: string;
    onImageChange: (file: File | null) => void;
    onDescriptionChange: (description: string) => void;
}

export function ImageInput({
    name,
    label,
    image,
    description,
    onImageChange,
    onDescriptionChange,
}: ImageInputProps) {
    const fileInputRef = useRef<HTMLInputElement>(null);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] || null;
        onImageChange(file);
        
        if (file) {
            const url = URL.createObjectURL(file);
            setPreviewUrl(url);
        } else {
            setPreviewUrl(null);
        }
    };

    return (
        <div className="flex flex-col gap-4 p-4 border rounded-md bg-muted/20">
            <div className="flex flex-col gap-2">
                <Label htmlFor={`${name}-file`}>{label}</Label>
                <div className="flex items-center gap-4">
                    <Input
                        id={`${name}-file`}
                        name={`${name}_file`}
                        type="file"
                        accept="image/*"
                        ref={fileInputRef}
                        onChange={handleFileChange}
                        className="cursor-pointer"
                    />
                </div>
            </div>
            
            {previewUrl && (
                <div className="mt-2 text-center">
                    <img 
                        src={previewUrl} 
                        alt="Preview" 
                        className="max-h-[200px] rounded-md object-contain border mx-auto" 
                    />
                </div>
            )}

            <div className="flex flex-col gap-2">
                <Label htmlFor={`${name}-desc`}>Image Description (Alt Text)</Label>
                <Input
                    id={`${name}-desc`}
                    name={`${name}_desc`}
                    type="text"
                    value={description}
                    onChange={(e) => onDescriptionChange(e.target.value)}
                    placeholder="Describe the image (e.g. 'A red car parked on the street')"
                />
            </div>
        </div>
    );
}
