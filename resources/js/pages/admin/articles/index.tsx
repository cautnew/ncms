import { Head, Link, router } from '@inertiajs/react';
import ArticleController from '@/actions/App/Http/Controllers/Admin/ArticleController';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { create, index } from '@/routes/admin/articles';

type ArticleRow = {
    id: number;
    slug: string;
    title: string;
    category: string;
    published_at: string;
};

export default function ArticlesIndex({
    articles,
}: {
    articles: ArticleRow[];
}) {
    return (
        <>
            <Head title="Artigos" />

            <div className="space-y-6 p-4">
                <div className="flex items-center justify-between">
                    <Heading
                        title="Artigos"
                        description="Conteúdo exibido na home e em /purinaeu/artigos"
                    />
                    <Button asChild>
                        <Link href={create()}>Novo artigo</Link>
                    </Button>
                </div>

                <div className="overflow-x-auto rounded-lg border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3 font-medium">Título</th>
                                <th className="p-3 font-medium">Categoria</th>
                                <th className="p-3 font-medium">
                                    Publicado em
                                </th>
                                <th className="p-3 font-medium">Slug</th>
                                <th className="p-3" />
                            </tr>
                        </thead>
                        <tbody>
                            {articles.map((article) => (
                                <tr key={article.id} className="border-t">
                                    <td className="p-3">{article.title}</td>
                                    <td className="p-3">{article.category}</td>
                                    <td className="p-3">
                                        {article.published_at}
                                    </td>
                                    <td className="p-3 text-muted-foreground">
                                        {article.slug}
                                    </td>
                                    <td className="space-x-2 p-3 text-right whitespace-nowrap">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            asChild
                                        >
                                            <Link
                                                href={ArticleController.edit.url(
                                                    { article: article.id },
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
                                                        `Excluir o artigo "${article.title}"?`,
                                                    )
                                                ) {
                                                    router.delete(
                                                        ArticleController.destroy.url(
                                                            {
                                                                article:
                                                                    article.id,
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
                            {articles.length === 0 && (
                                <tr>
                                    <td
                                        className="p-3 text-muted-foreground"
                                        colSpan={5}
                                    >
                                        Nenhum artigo cadastrado.
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

ArticlesIndex.layout = {
    breadcrumbs: [{ title: 'Artigos', href: index() }],
};
