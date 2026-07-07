import { Form, Head } from '@inertiajs/react';
import ArticleController from '@/actions/App/Http/Controllers/Admin/ArticleController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { index } from '@/routes/admin/articles';

type Article = {
    id: number;
    slug: string;
    title: string;
    excerpt: string;
    category: string;
    author: string;
    published_at: string;
    views: string;
    image: string;
    body: string | null;
};

export default function ArticleEdit({ article }: { article: Article }) {
    return (
        <>
            <Head title={`Editar: ${article.title}`} />

            <div className="max-w-2xl space-y-6 p-4">
                <Heading title="Editar artigo" />

                <Form
                    {...ArticleController.update.form({ article: article.id })}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="title">Título</Label>
                                <Input
                                    id="title"
                                    name="title"
                                    defaultValue={article.title}
                                    required
                                />
                                <InputError message={errors.title} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="slug">Slug</Label>
                                <Input
                                    id="slug"
                                    name="slug"
                                    defaultValue={article.slug}
                                    required
                                />
                                <InputError message={errors.slug} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="excerpt">Resumo</Label>
                                <Textarea
                                    id="excerpt"
                                    name="excerpt"
                                    defaultValue={article.excerpt}
                                    required
                                />
                                <InputError message={errors.excerpt} />
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="grid gap-2">
                                    <Label htmlFor="category">Categoria</Label>
                                    <Input
                                        id="category"
                                        name="category"
                                        defaultValue={article.category}
                                        required
                                    />
                                    <InputError message={errors.category} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="author">Autor</Label>
                                    <Input
                                        id="author"
                                        name="author"
                                        defaultValue={article.author}
                                        required
                                    />
                                    <InputError message={errors.author} />
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="grid gap-2">
                                    <Label htmlFor="published_at">
                                        Publicado em
                                    </Label>
                                    <Input
                                        id="published_at"
                                        type="date"
                                        name="published_at"
                                        defaultValue={article.published_at}
                                        required
                                    />
                                    <InputError message={errors.published_at} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="views">Visualizações</Label>
                                    <Input
                                        id="views"
                                        name="views"
                                        defaultValue={article.views}
                                        required
                                    />
                                    <InputError message={errors.views} />
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="image">URL da imagem</Label>
                                <Input
                                    id="image"
                                    name="image"
                                    defaultValue={article.image}
                                    required
                                />
                                <InputError message={errors.image} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="body">Corpo do artigo</Label>
                                <Textarea
                                    id="body"
                                    name="body"
                                    rows={10}
                                    defaultValue={article.body ?? ''}
                                />
                                <InputError message={errors.body} />
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

ArticleEdit.layout = {
    breadcrumbs: [
        { title: 'Artigos', href: index() },
        { title: 'Editar', href: '' },
    ],
};
