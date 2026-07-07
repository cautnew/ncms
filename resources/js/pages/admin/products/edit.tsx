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

type Product = {
    id: number;
    slug: string;
    name: string;
    category: string;
    price: string;
    old_price: string | null;
    rating: string;
    reviews: number;
    image: string;
    description: string;
    specs: Array<{ label: string; value: string }>;
    usage_text: string;
    ingredients_text: string;
};

export default function ProductEdit({ product }: { product: Product }) {
    return (
        <>
            <Head title={`Editar: ${product.name}`} />

            <div className="max-w-2xl space-y-6 p-4">
                <Heading title="Editar produto" />

                <Form
                    {...ProductController.update.form({ product: product.id })}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="name">Nome</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    defaultValue={product.name}
                                    required
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="slug">Slug</Label>
                                <Input
                                    id="slug"
                                    name="slug"
                                    defaultValue={product.slug}
                                    required
                                />
                                <InputError message={errors.slug} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="category">Categoria</Label>
                                <Input
                                    id="category"
                                    name="category"
                                    defaultValue={product.category}
                                    required
                                />
                                <InputError message={errors.category} />
                            </div>

                            <div className="grid grid-cols-3 gap-4">
                                <div className="grid gap-2">
                                    <Label htmlFor="price">Preço</Label>
                                    <Input
                                        id="price"
                                        name="price"
                                        defaultValue={product.price}
                                        required
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
                                        defaultValue={product.old_price ?? ''}
                                    />
                                    <InputError message={errors.old_price} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="rating">Avaliação</Label>
                                    <Input
                                        id="rating"
                                        name="rating"
                                        defaultValue={product.rating}
                                        required
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
                                    defaultValue={product.reviews}
                                    required
                                />
                                <InputError message={errors.reviews} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="image">URL da imagem</Label>
                                <Input
                                    id="image"
                                    name="image"
                                    defaultValue={product.image}
                                    required
                                />
                                <InputError message={errors.image} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="description">Descrição</Label>
                                <Textarea
                                    id="description"
                                    name="description"
                                    defaultValue={product.description}
                                    required
                                />
                                <InputError message={errors.description} />
                            </div>

                            <div className="grid gap-2">
                                <Label>Especificações</Label>
                                <RepeaterField
                                    name="specs"
                                    addLabel="Adicionar especificação"
                                    defaultValue={product.specs}
                                    fields={[
                                        { name: 'label', label: 'Nome' },
                                        { name: 'value', label: 'Valor' },
                                    ]}
                                />
                                <InputError message={errors.specs} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="usage_text">Modo de uso</Label>
                                <Textarea
                                    id="usage_text"
                                    name="usage_text"
                                    defaultValue={product.usage_text}
                                    required
                                />
                                <InputError message={errors.usage_text} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="ingredients_text">
                                    Ingredientes
                                </Label>
                                <Textarea
                                    id="ingredients_text"
                                    name="ingredients_text"
                                    defaultValue={product.ingredients_text}
                                    required
                                />
                                <InputError message={errors.ingredients_text} />
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

ProductEdit.layout = {
    breadcrumbs: [
        { title: 'Produtos', href: index() },
        { title: 'Editar', href: '' },
    ],
};
