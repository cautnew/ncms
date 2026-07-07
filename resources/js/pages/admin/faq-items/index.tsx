import { Head, Link, router } from '@inertiajs/react';
import FaqItemController from '@/actions/App/Http/Controllers/Admin/FaqItemController';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { create, index } from '@/routes/admin/faq-items';

type ItemRow = {
    id: number;
    question: string;
    order: number;
    category: { id: number; name: string };
};

export default function FaqItemsIndex({ items }: { items: ItemRow[] }) {
    return (
        <>
            <Head title="Perguntas de FAQ" />

            <div className="space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <Heading
                        title="Perguntas de FAQ"
                        description="Exibidas em /purinaeu/faq, agrupadas por categoria"
                    />
                    <Button asChild>
                        <Link href={create()}>Nova pergunta</Link>
                    </Button>
                </div>

                <div className="overflow-x-auto rounded-lg border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3 font-medium">Pergunta</th>
                                <th className="p-3 font-medium">Categoria</th>
                                <th className="p-3 font-medium">Ordem</th>
                                <th className="p-3" />
                            </tr>
                        </thead>
                        <tbody>
                            {items.map((item) => (
                                <tr key={item.id} className="border-t">
                                    <td className="p-3">{item.question}</td>
                                    <td className="p-3">
                                        {item.category.name}
                                    </td>
                                    <td className="p-3">{item.order}</td>
                                    <td className="space-x-2 p-3 text-right whitespace-nowrap">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            asChild
                                        >
                                            <Link
                                                href={FaqItemController.edit.url(
                                                    { faqItem: item.id },
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
                                                        `Excluir a pergunta "${item.question}"?`,
                                                    )
                                                ) {
                                                    router.delete(
                                                        FaqItemController.destroy.url(
                                                            {
                                                                faqItem:
                                                                    item.id,
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
                            {items.length === 0 && (
                                <tr>
                                    <td
                                        className="p-3 text-muted-foreground"
                                        colSpan={4}
                                    >
                                        Nenhuma pergunta cadastrada.
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

FaqItemsIndex.layout = {
    breadcrumbs: [{ title: 'Perguntas de FAQ', href: index() }],
};
