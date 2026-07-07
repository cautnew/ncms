import { Form, Head } from '@inertiajs/react';
import PageContentController from '@/actions/App/Http/Controllers/Admin/PageContentController';
import { RepeaterField } from '@/components/admin/repeater-field';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { home } from '@/routes/admin/pages';

type Hero = {
    title: string;
    subtitle: string;
    button_label: string;
    button_href: string;
};
type Cta = {
    title: string;
    text: string;
    button_label: string;
    button_href: string;
};
type InfoBlock = { icon: string; title: string; text: string };

export default function HomePageContent({
    hero,
    info_blocks: infoBlocks,
    cta,
}: {
    hero: Hero;
    info_blocks: InfoBlock[];
    cta: Cta;
}) {
    return (
        <>
            <Head title="Conteúdo da Home" />

            <div className="max-w-2xl space-y-10 p-4">
                <Heading
                    title="Conteúdo da Home"
                    description="Textos exibidos em /purinaeu"
                />

                <Form
                    {...PageContentController.updateHome.form()}
                    className="space-y-10"
                >
                    {({ processing, errors }) => (
                        <>
                            <section className="space-y-4">
                                <h3 className="font-medium">Hero</h3>
                                <div className="grid gap-2">
                                    <Label htmlFor="hero.title">Título</Label>
                                    <Input
                                        id="hero.title"
                                        name="hero[title]"
                                        defaultValue={hero.title}
                                        required
                                    />
                                    <InputError
                                        message={errors['hero.title']}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="hero.subtitle">
                                        Subtítulo
                                    </Label>
                                    <Textarea
                                        id="hero.subtitle"
                                        name="hero[subtitle]"
                                        defaultValue={hero.subtitle}
                                        required
                                    />
                                    <InputError
                                        message={errors['hero.subtitle']}
                                    />
                                </div>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="grid gap-2">
                                        <Label htmlFor="hero.button_label">
                                            Texto do botão
                                        </Label>
                                        <Input
                                            id="hero.button_label"
                                            name="hero[button_label]"
                                            defaultValue={hero.button_label}
                                            required
                                        />
                                        <InputError
                                            message={
                                                errors['hero.button_label']
                                            }
                                        />
                                    </div>
                                    <div className="grid gap-2">
                                        <Label htmlFor="hero.button_href">
                                            Link do botão
                                        </Label>
                                        <Input
                                            id="hero.button_href"
                                            name="hero[button_href]"
                                            defaultValue={hero.button_href}
                                            required
                                        />
                                        <InputError
                                            message={errors['hero.button_href']}
                                        />
                                    </div>
                                </div>
                            </section>

                            <section className="space-y-4">
                                <h3 className="font-medium">
                                    Blocos de destaque
                                </h3>
                                <RepeaterField
                                    name="info_blocks"
                                    addLabel="Adicionar bloco"
                                    defaultValue={infoBlocks}
                                    fields={[
                                        {
                                            name: 'icon',
                                            label: 'Ícone (emoji)',
                                        },
                                        { name: 'title', label: 'Título' },
                                        {
                                            name: 'text',
                                            label: 'Texto',
                                            type: 'textarea',
                                        },
                                    ]}
                                />
                                <InputError message={errors.info_blocks} />
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

HomePageContent.layout = {
    breadcrumbs: [{ title: 'Conteúdo da Home', href: home() }],
};
