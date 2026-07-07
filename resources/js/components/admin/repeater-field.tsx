import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

type FieldDef = {
    name: string;
    label: string;
    type?: 'text' | 'textarea';
};

export function RepeaterField({
    name,
    fields,
    defaultValue,
    addLabel = 'Adicionar item',
}: {
    name: string;
    fields: FieldDef[];
    defaultValue: Array<Record<string, string>>;
    addLabel?: string;
}) {
    const emptyRow = () =>
        Object.fromEntries(fields.map((field) => [field.name, '']));
    const [rows, setRows] = useState<Array<Record<string, string>>>(
        defaultValue.length > 0 ? defaultValue : [emptyRow()],
    );

    const updateRow = (index: number, field: string, value: string) => {
        setRows((current) =>
            current.map((row, rowIndex) =>
                rowIndex === index ? { ...row, [field]: value } : row,
            ),
        );
    };

    return (
        <div className="space-y-4">
            {rows.map((row, index) => (
                <div key={index} className="grid gap-3 rounded-lg border p-4">
                    {fields.map((field) => (
                        <div key={field.name} className="grid gap-2">
                            <Label htmlFor={`${name}-${index}-${field.name}`}>
                                {field.label}
                            </Label>
                            {field.type === 'textarea' ? (
                                <Textarea
                                    id={`${name}-${index}-${field.name}`}
                                    name={`${name}[${index}][${field.name}]`}
                                    value={row[field.name]}
                                    onChange={(event) =>
                                        updateRow(
                                            index,
                                            field.name,
                                            event.target.value,
                                        )
                                    }
                                    required
                                />
                            ) : (
                                <Input
                                    id={`${name}-${index}-${field.name}`}
                                    name={`${name}[${index}][${field.name}]`}
                                    value={row[field.name]}
                                    onChange={(event) =>
                                        updateRow(
                                            index,
                                            field.name,
                                            event.target.value,
                                        )
                                    }
                                    required
                                />
                            )}
                        </div>
                    ))}
                    <Button
                        type="button"
                        variant="outline"
                        size="sm"
                        className="justify-self-start"
                        disabled={rows.length <= 1}
                        onClick={() =>
                            setRows((current) =>
                                current.filter(
                                    (_, rowIndex) => rowIndex !== index,
                                ),
                            )
                        }
                    >
                        Remover
                    </Button>
                </div>
            ))}

            <Button
                type="button"
                variant="secondary"
                size="sm"
                onClick={() => setRows((current) => [...current, emptyRow()])}
            >
                {addLabel}
            </Button>
        </div>
    );
}
