import { Form, Head } from '@inertiajs/react';
import PageContentController from '@/actions/App/Http/Controllers/Admin/PageContentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { articles } from '@/routes/admin/pages';

type Intro = { title: string; text: string };

export default function ArticlesPageContent({ intro }: { intro: Intro }) {
    return (
        <>
            <Head title="Conteúdo da página Artigos" />

            <div className="max-w-2xl space-y-10 p-4">
                <Heading
                    title="Conteúdo da página Artigos"
                    description="Texto de introdução exibido em /purinaeu/artigos (os artigos são gerenciados em Artigos)"
                />

                <Form
                    {...PageContentController.updateArticles.form()}
                    className="space-y-10"
                >
                    {({ processing, errors }) => (
                        <>
                            <section className="space-y-4">
                                <div className="grid gap-2">
                                    <Label htmlFor="intro.title">Título</Label>
                                    <Input
                                        id="intro.title"
                                        name="intro[title]"
                                        defaultValue={intro.title}
                                        required
                                    />
                                    <InputError
                                        message={errors['intro.title']}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="intro.text">Texto</Label>
                                    <Textarea
                                        id="intro.text"
                                        name="intro[text]"
                                        defaultValue={intro.text}
                                        required
                                    />
                                    <InputError
                                        message={errors['intro.text']}
                                    />
                                </div>
                            </section>

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

ArticlesPageContent.layout = {
    breadcrumbs: [{ title: 'Conteúdo da página Artigos', href: articles() }],
};
