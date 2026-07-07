import { Form, Head } from '@inertiajs/react';
import PageContentController from '@/actions/App/Http/Controllers/Admin/PageContentController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { faq } from '@/routes/admin/pages';

type Intro = { title: string; text: string };
type Cta = {
    title: string;
    text: string;
    button_label: string;
    button_href: string;
};

export default function FaqPageContent({
    intro,
    cta,
}: {
    intro: Intro;
    cta: Cta;
}) {
    return (
        <>
            <Head title="Conteúdo da página FAQ" />

            <div className="max-w-2xl space-y-10 p-4">
                <Heading
                    title="Conteúdo da página FAQ"
                    description="Textos exibidos em /purinaeu/faq (as perguntas são gerenciadas em Perguntas de FAQ)"
                />

                <Form
                    {...PageContentController.updateFaq.form()}
                    className="space-y-10"
                >
                    {({ processing, errors }) => (
                        <>
                            <section className="space-y-4">
                                <h3 className="font-medium">Introdução</h3>
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

                            <section className="space-y-4">
                                <h3 className="font-medium">
                                    Banner de chamada
                                </h3>
                                <div className="grid gap-2">
                                    <Label htmlFor="cta.title">Título</Label>
                                    <Input
                                        id="cta.title"
                                        name="cta[title]"
                                        defaultValue={cta.title}
                                        required
                                    />
                                    <InputError message={errors['cta.title']} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="cta.text">Texto</Label>
                                    <Textarea
                                        id="cta.text"
                                        name="cta[text]"
                                        defaultValue={cta.text}
                                        required
                                    />
                                    <InputError message={errors['cta.text']} />
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="grid gap-2">
                                        <Label htmlFor="cta.button_label">
                                            Texto do botão
                                        </Label>
                                        <Input
                                            id="cta.button_label"
                                            name="cta[button_label]"
                                            defaultValue={cta.button_label}
                                            required
                                        />
                                        <InputError
                                            message={errors['cta.button_label']}
                                        />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="cta.button_href">
                                            Link do botão
                                        </Label>
                                        <Input
                                            id="cta.button_href"
                                            name="cta[button_href]"
                                            defaultValue={cta.button_href}
                                            required
                                        />
                                        <InputError
                                            message={errors['cta.button_href']}
                                        />
                                    </div>
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

FaqPageContent.layout = {
    breadcrumbs: [{ title: 'Conteúdo da página FAQ', href: faq() }],
};
