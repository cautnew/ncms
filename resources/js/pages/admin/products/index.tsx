import { Head, Link, router } from '@inertiajs/react';
import ProductController from '@/actions/App/Http/Controllers/Admin/ProductController';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { create, index } from '@/routes/admin/products';

type ProductRow = {
    id: number;
    slug: string;
    name: string;
    category: string;
    price: string;
};

export default function ProductsIndex({
    products,
}: {
    products: ProductRow[];
}) {
    return (
        <>
            <Head title="Produtos" />

            <div className="space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <Heading
                        title="Produtos"
                        description="Catálogo exibido em /purinaeu/produto"
                    />
                    <Button asChild>
                        <Link href={create()}>Novo produto</Link>
                    </Button>
                </div>

                <div className="overflow-x-auto rounded-lg border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3 font-medium">Nome</th>
                                <th className="p-3 font-medium">Categoria</th>
                                <th className="p-3 font-medium">Preço</th>
                                <th className="p-3 font-medium">Slug</th>
                                <th className="p-3" />
                            </tr>
                        </thead>
                        <tbody>
                            {products.map((product) => (
                                <tr key={product.id} className="border-t">
                                    <td className="p-3">{product.name}</td>
                                    <td className="p-3">{product.category}</td>
                                    <td className="p-3">{product.price}</td>
                                    <td className="p-3 text-muted-foreground">
                                        {product.slug}
                                    </td>
                                    <td className="space-x-2 p-3 text-right whitespace-nowrap">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            asChild
                                        >
                                            <Link
                                                href={ProductController.edit.url(
                                                    { product: product.id },
                                                )}
                                            >
                                                Editar
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            onClick={() => {
                                                if (
                                                    confirm(
                                                        `Excluir o produto "${product.name}"?`,
                                                    )
                                                ) {
                                                    router.delete(
                                                        ProductController.destroy.url(
                                                            {
                                                                product:
                                                                    product.id,
                                                            },
                                                        ),
                                                    );
                                                }
                                            }}
                                        >
                                            Excluir
                                        </Button>
                                    </td>
                                </tr>
                            ))}
                            {products.length === 0 && (
                                <tr>
                                    <td
                                        className="p-3 text-muted-foreground"
                                        colSpan={5}
                                    >
                                        Nenhum produto cadastrado.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

ProductsIndex.layout = {
    breadcrumbs: [{ title: 'Produtos', href: index() }],
};
