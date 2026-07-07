import { Form, Head } from '@inertiajs/react';
import FaqItemController from '@/actions/App/Http/Controllers/Admin/FaqItemController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { index } from '@/routes/admin/faq-items';

export default function FaqItemCreate({
    categories,
}: {
    categories: Array<{ id: number; name: string }>;
}) {
    return (
        <>
            <Head title="Nova pergunta de FAQ" />

            <div className="max-w-lg space-y-6 p-4">
                <Heading title="Nova pergunta de FAQ" />

                <Form {...FaqItemController.store.form()} className="space-y-6">
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="faq_category_id">
                                    Categoria
                                </Label>
                                <select
                                    id="faq_category_id"
                                    name="faq_category_id"
                                    required
                                    className="h-9 w-full rounded-md border border-input bg-transparent px-3 text-sm shadow-xs outline-none"
                                >
                                    {categories.map((category) => (
                                        <option
                                            key={category.id}
                                            value={category.id}
                                        >
                                            {category.name}
                                        </option>
                                    ))}
                                </select>
                                <InputError message={errors.faq_category_id} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="question">Pergunta</Label>
                                <Input id="question" name="question" required />
                                <InputError message={errors.question} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="answer">Resposta</Label>
                                <Textarea id="answer" name="answer" required />
                                <InputError message={errors.answer} />
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

FaqItemCreate.layout = {
    breadcrumbs: [
        { title: 'Perguntas de FAQ', href: index() },
        { title: 'Nova pergunta', href: '' },
    ],
};
