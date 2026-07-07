import { Form, Head } from '@inertiajs/react';
import FaqCategoryController from '@/actions/App/Http/Controllers/Admin/FaqCategoryController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/admin/faq-categories';

type FaqCategory = {
    id: number;
    name: string;
    order: number;
};

export default function FaqCategoryEdit({
    category,
}: {
    category: FaqCategory;
}) {
    return (
        <>
            <Head title={`Editar: ${category.name}`} />

            <div className="max-w-lg space-y-6 p-4">
                <Heading title="Editar categoria de FAQ" />

                <Form
                    {...FaqCategoryController.update.form({
                        faqCategory: category.id,
                    })}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nome</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    defaultValue={category.name}
                                    required
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="order">Ordem</Label>
                                <Input
                                    id="order"
                                    type="number"
                                    min={0}
                                    name="order"
                                    defaultValue={category.order}
                                    required
                                />
                                <InputError message={errors.order} />
                            </div>

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

FaqCategoryEdit.layout = {
    breadcrumbs: [
        { title: 'Categorias de FAQ', href: index() },
        { title: 'Editar', href: '' },
    ],
};
