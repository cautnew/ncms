import { Form, Head } from '@inertiajs/react';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import { RepeaterField } from '@/components/admin/repeater-field';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { index } from '@/routes/admin/products';

export default function ProductCreate() {
    return (
        <>
            <Head title="Novo produto" />

            <div className="max-w-2xl space-y-6 p-4">
                <Heading title="Novo produto" />

                <Form {...ProductController.store.form()} className="space-y-6">
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nome</Label>
                                <Input id="name" name="name" required />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="slug">Slug</Label>
                                <Input
                                    id="slug"
                                    name="slug"
                                    required
                                    placeholder="meu-produto"
                                />
                                <InputError message={errors.slug} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="category">Categoria</Label>
                                <Input id="category" name="category" required />
                                <InputError message={errors.category} />
                            </div>

                            <div className="grid grid-cols-3 gap-4">
                                <div className="grid gap-2">
                                    <Label htmlFor="price">Preço</Label>
                                    <Input
                                        id="price"
                                        name="price"
                                        required
                                        placeholder="€ 42,90"
                                    />
                                    <InputError message={errors.price} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="old_price">
                                        Preço antigo
                                    </Label>
                                    <Input
                                        id="old_price"
                                        name="old_price"
                                        placeholder="€ 49,90"
                                    />
                                    <InputError message={errors.old_price} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="rating">Avaliação</Label>
                                    <Input
                                        id="rating"
                                        name="rating"
                                        required
                                        placeholder="4,8"
                                    />
                                    <InputError message={errors.rating} />
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="reviews">
                                    Número de avaliações
                                </Label>
                                <Input
                                    id="reviews"
                                    type="number"
                                    min={0}
                                    name="reviews"
                                    required
                                />
                                <InputError message={errors.reviews} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="image">URL da imagem</Label>
                                <Input id="image" name="image" required />
                                <InputError message={errors.image} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="description">Descrição</Label>
                                <Textarea
                                    id="description"
                                    name="description"
                                    required
                                />
                                <InputError message={errors.description} />
                            </div>

                            <div className="grid gap-2">
                                <Label>Especificações</Label>
                                <RepeaterField
                                    name="specs"
                                    addLabel="Adicionar especificação"
                                    defaultValue={[]}
                                    fields={[
                                        { name: 'label', label: 'Nome' },
                                        { name: 'value', label: 'Valor' },
                                    ]}
                                />
                                <InputError message={errors.specs} />
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

ProductCreate.layout = {
    breadcrumbs: [
        { title: 'Produtos', href: index() },
        { title: 'Novo produto', href: '' },
    ],
};
