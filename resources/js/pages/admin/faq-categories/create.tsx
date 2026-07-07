import { Form, Head } from '@inertiajs/react';
import FaqCategoryController from '@/actions/App/Http/Controllers/Admin/FaqCategoryController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/admin/faq-categories';

export default function FaqCategoryCreate() {
    return (
        <>
            <Head title="Nova categoria de FAQ" />

            <div className="max-w-lg space-y-6 p-4">
                <Heading title="Nova categoria de FAQ" />

                <Form
                    {...FaqCategoryController.store.form()}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nome</Label>
                                <Input id="name" name="name" required />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="order">Ordem</Label>
                                <Input
                                    id="order"
                                    type="number"
                                    min={0}
                                    name="order"
                                    defaultValue={0}
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

FaqCategoryCreate.layout = {
    breadcrumbs: [
        { title: 'Categorias de FAQ', href: index() },
        { title: 'Nova categoria', href: '' },
    ],
};
