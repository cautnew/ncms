import { Head, Link, router } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';

type BlockRow = {
    id: number;
    type: string;
    type_label: string;
    summary: string;
    is_first: boolean;
    is_last: boolean;
    edit_url: string;
    move_up_url: string;
    move_down_url: string;
    delete_url: string;
};

type AddLink = {
    type: string;
    label: string;
    href: string;
};

export default function BlocksIndex({
    owner_label: ownerLabel,
    back_href: backHref,
    back_label: backLabel,
    blocks,
    add_links: addLinks,
}: {
    owner_label: string;
    back_href: string;
    back_label: string;
    blocks: BlockRow[];
    add_links: AddLink[];
}) {
    return (
        <>
            <Head title={`Blocos — ${ownerLabel}`} />

            <div className="space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <Heading
                        title={`Blocos de conteúdo — ${ownerLabel}`}
                        description="Adicione, edite, reordene ou remova os blocos que formam o conteúdo."
                    />
                    <Button variant="outline" asChild>
                        <Link href={backHref}>{backLabel}</Link>
                    </Button>
                </div>

                <div className="overflow-x-auto rounded-lg border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3 font-medium">Tipo</th>
                                <th className="p-3 font-medium">Resumo</th>
                                <th className="p-3" />
                            </tr>
                        </thead>
                        <tbody>
                            {blocks.map((block) => (
                                <tr key={block.id} className="border-t">
                                    <td className="p-3">{block.type_label}</td>
                                    <td className="p-3">{block.summary}</td>
                                    <td className="space-x-2 p-3 text-right whitespace-nowrap">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            disabled={block.is_first}
                                            onClick={() =>
                                                router.post(block.move_up_url)
                                            }
                                        >
                                            Subir
                                        </Button>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            disabled={block.is_last}
                                            onClick={() =>
                                                router.post(block.move_down_url)
                                            }
                                        >
                                            Descer
                                        </Button>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            asChild
                                        >
                                            <Link href={block.edit_url}>
                                                Editar
                                            </Link>
                                        </Button>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            onClick={() => {
                                                if (
                                                    confirm(
                                                        `Excluir o bloco "${block.type_label}"?`,
                                                    )
                                                ) {
                                                    router.delete(
                                                        block.delete_url,
                                                    );
                                                }
                                            }}
                                        >
                                            Excluir
                                        </Button>
                                    </td>
                                </tr>
                            ))}
                            {blocks.length === 0 && (
                                <tr>
                                    <td
                                        className="p-3 text-muted-foreground"
                                        colSpan={3}
                                    >
                                        Nenhum bloco cadastrado.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="space-y-2">
                    <Heading variant="small" title="Adicionar bloco" />
                    <div className="flex flex-wrap gap-2">
                        {addLinks.map((link) => (
                            <Button key={link.type} variant="secondary" asChild>
                                <Link href={link.href}>{link.label}</Link>
                            </Button>
                        ))}
                    </div>
                </div>
            </div>
        </>
    );
}

BlocksIndex.layout = {
    breadcrumbs: [{ title: 'Blocos de conteúdo', href: '' }],
};
