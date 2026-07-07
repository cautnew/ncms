import { Head, Link, router } from '@inertiajs/react';
import FaqCategoryController from '@/actions/App/Http/Controllers/Admin/FaqCategoryController';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { create, index } from '@/routes/admin/faq-categories';

type CategoryRow = {
    id: number;
    name: string;
    order: number;
    items_count: number;
};

export default function FaqCategoriesIndex({
    categories,
}: {
    categories: CategoryRow[];
}) {
    return (
        <>
            <Head title="Categorias de FAQ" />

            <div className="space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <Heading
                        title="Categorias de FAQ"
                        description="Agrupam as perguntas exibidas em /purinaeu/faq"
                    />
                    <Button asChild>
                        <Link href={create()}>Nova categoria</Link>
                    </Button>
                </div>

                <div className="overflow-x-auto rounded-lg border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3 font-medium">Nome</th>
                                <th className="p-3 font-medium">Ordem</th>
                                <th className="p-3 font-medium">Perguntas</th>
                                <th className="p-3" />
                            </tr>
                        </thead>
                        <tbody>
                            {categories.map((category) => (
                                <tr key={category.id} className="border-t">
                                    <td className="p-3">{category.name}</td>
                                    <td className="p-3">{category.order}</td>
                                    <td className="p-3">
                                        {category.items_count}
                                    </td>
                                    <td className="space-x-2 p-3 text-right whitespace-nowrap">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            asChild
                                        >
                                            <Link
                                                href={FaqCategoryController.edit.url(
                                                    {
                                                        faqCategory:
                                                            category.id,
                                                    },
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
                                                        `Excluir a categoria "${category.name}"?`,
                                                    )
                                                ) {
                                                    router.delete(
                                                        FaqCategoryController.destroy.url(
                                                            {
                                                                faqCategory:
                                                                    category.id,
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
                            {categories.length === 0 && (
                                <tr>
                                    <td
                                        className="p-3 text-muted-foreground"
                                        colSpan={4}
                                    >
                                        Nenhuma categoria cadastrada.
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

FaqCategoriesIndex.layout = {
    breadcrumbs: [{ title: 'Categorias de FAQ', href: index() }],
};
