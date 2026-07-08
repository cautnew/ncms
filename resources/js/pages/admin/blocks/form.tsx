import { Form, Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

type FieldDef = {
    name: string;
    label: string;
    type: 'text' | 'textarea' | 'number';
    required: boolean;
};

type Block = {
    id: number;
    data: Record<string, string | number | null>;
};

export default function BlockForm({
    owner_label: ownerLabel,
    index_href: indexHref,
    type,
    type_label: typeLabel,
    fields,
    block,
    action,
    method,
}: {
    owner_label: string;
    back_href: string;
    back_label: string;
    index_href: string;
    type: string;
    type_label: string;
    fields: FieldDef[];
    block: Block | null;
    action: string;
    method: 'post' | 'put';
}) {
    return (
        <>
            <Head title={`${block ? 'Editar' : 'Novo'} bloco — ${typeLabel}`} />

            <div className="max-w-2xl space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <Heading
                        title={`${block ? 'Editar' : 'Novo'} bloco: ${typeLabel}`}
                        description={ownerLabel}
                    />
                    <Button variant="outline" asChild>
                        <Link href={indexHref}>Voltar</Link>
                    </Button>
                </div>

                {fields.length === 0 && (
                    <p className="text-sm text-muted-foreground">
                        Este bloco não tem campos editáveis — sua posição ainda
                        pode ser alterada na lista de blocos.
                    </p>
                )}

                <Form action={action} method={method} className="space-y-6">
                    {({ processing, errors }) => (
                        <>
                            <input type="hidden" name="type" value={type} />

                            {fields.map((field) => {
                                const fieldErrors = errors as Record<
                                    string,
                                    string | undefined
                                >;
                                const defaultValue =
                                    block?.data[field.name] ?? '';
                                const name = `data[${field.name}]`;
                                const errorKey = `data.${field.name}`;

                                return (
                                    <div
                                        key={field.name}
                                        className="grid gap-2"
                                    >
                                        <Label htmlFor={name}>
                                            {field.label}
                                        </Label>
                                        {field.type === 'textarea' ? (
                                            <Textarea
                                                id={name}
                                                name={name}
                                                defaultValue={defaultValue}
                                                required={field.required}
                                                rows={6}
                                            />
                                        ) : (
                                            <Input
                                                id={name}
                                                name={name}
                                                type={
                                                    field.type === 'number'
                                                        ? 'number'
                                                        : 'text'
                                                }
                                                defaultValue={defaultValue}
                                                required={field.required}
                                            />
                                        )}
                                        <InputError
                                            message={fieldErrors[errorKey]}
                                        />
                                    </div>
                                );
                            })}

                            <div className="flex items-center gap-4">
                                <Button disabled={processing}>Salvar</Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

BlockForm.layout = {
    breadcrumbs: [{ title: 'Blocos de conteúdo', href: '' }],
};
