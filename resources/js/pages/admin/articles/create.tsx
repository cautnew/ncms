import { Form, Head } from '@inertiajs/react';
import ArticleController from '@/actions/App/Http/Controllers/Admin/ArticleController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { index } from '@/routes/admin/articles';

export default function ArticleCreate() {
    return (
        <>
            <Head title="Novo artigo" />

            <div className="max-w-2xl space-y-6 p-4">
                <Heading title="Novo artigo" />

                <Form {...ArticleController.store.form()} className="space-y-6">
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="title">Título</Label>
                                <Input id="title" name="title" required />
                                <InputError message={errors.title} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="slug">Slug</Label>
                                <Input
                                    id="slug"
                                    name="slug"
                                    required
                                    placeholder="meu-artigo"
                                />
                                <InputError message={errors.slug} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="excerpt">Resumo</Label>
                                <Textarea
                                    id="excerpt"
                                    name="excerpt"
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
                                        required
                                    />
                                    <InputError message={errors.category} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="author">Autor</Label>
                                    <Input id="author" name="author" required />
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
                                        required
                                    />
                                    <InputError message={errors.published_at} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="views">Visualizações</Label>
                                    <Input
                                        id="views"
                                        name="views"
                                        required
                                        placeholder="18,4k"
                                    />
                                    <InputError message={errors.views} />
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="image">URL da imagem</Label>
                                <Input id="image" name="image" required />
                                <InputError message={errors.image} />
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

ArticleCreate.layout = {
    breadcrumbs: [
        { title: 'Artigos', href: index() },
        { title: 'Novo artigo', href: '' },
    ],
};
