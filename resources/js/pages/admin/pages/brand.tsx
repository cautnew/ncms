import { Form, Head } from '@inertiajs/react';
import PageContentController from '@/actions/App/Http/Controllers/Admin/PageContentController';
import { RepeaterField } from '@/components/admin/repeater-field';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { brand } from '@/routes/admin/pages';

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
type StoryHistory = {
    title: string;
    text1: string;
    text2: string;
    image: string;
};
type StorySustainability = { title: string; text: string; image: string };
type Stat = { number: string; label: string };
type Value = { icon: string; title: string; text: string };
type Testimonial = { quote: string; author: string };

export default function BrandPageContent({
    hero,
    story_history: storyHistory,
    story_sustainability: storySustainability,
    stats,
    values,
    testimonial,
    cta,
}: {
    hero: Hero;
    story_history: StoryHistory;
    story_sustainability: StorySustainability;
    stats: Stat[];
    values: Value[];
    testimonial: Testimonial;
    cta: Cta;
}) {
    return (
        <>
            <Head title="Conteúdo da página Marca" />

            <div className="max-w-2xl space-y-10 p-4">
                <Heading
                    title="Conteúdo da página Marca"
                    description="Textos exibidos em /purinaeu/marca"
                />

                <Form
                    {...PageContentController.updateBrand.form()}
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
                                <h3 className="font-medium">Nossa história</h3>
                                <div className="grid gap-2">
                                    <Label htmlFor="story_history.title">
                                        Título
                                    </Label>
                                    <Input
                                        id="story_history.title"
                                        name="story_history[title]"
                                        defaultValue={storyHistory.title}
                                        required
                                    />
                                    <InputError
                                        message={errors['story_history.title']}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="story_history.text1">
                                        Parágrafo 1
                                    </Label>
                                    <Textarea
                                        id="story_history.text1"
                                        name="story_history[text1]"
                                        defaultValue={storyHistory.text1}
                                        required
                                    />
                                    <InputError
                                        message={errors['story_history.text1']}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="story_history.text2">
                                        Parágrafo 2
                                    </Label>
                                    <Textarea
                                        id="story_history.text2"
                                        name="story_history[text2]"
                                        defaultValue={storyHistory.text2}
                                        required
                                    />
                                    <InputError
                                        message={errors['story_history.text2']}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="story_history.image">
                                        URL da imagem
                                    </Label>
                                    <Input
                                        id="story_history.image"
                                        name="story_history[image]"
                                        defaultValue={storyHistory.image}
                                        required
                                    />
                                    <InputError
                                        message={errors['story_history.image']}
                                    />
                                </div>
                            </section>

                            <section className="space-y-4">
                                <h3 className="font-medium">
                                    Sustentabilidade
                                </h3>
                                <div className="grid gap-2">
                                    <Label htmlFor="story_sustainability.title">
                                        Título
                                    </Label>
                                    <Input
                                        id="story_sustainability.title"
                                        name="story_sustainability[title]"
                                        defaultValue={storySustainability.title}
                                        required
                                    />
                                    <InputError
                                        message={
                                            errors['story_sustainability.title']
                                        }
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="story_sustainability.text">
                                        Texto
                                    </Label>
                                    <Textarea
                                        id="story_sustainability.text"
                                        name="story_sustainability[text]"
                                        defaultValue={storySustainability.text}
                                        required
                                    />
                                    <InputError
                                        message={
                                            errors['story_sustainability.text']
                                        }
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="story_sustainability.image">
                                        URL da imagem
                                    </Label>
                                    <Input
                                        id="story_sustainability.image"
                                        name="story_sustainability[image]"
                                        defaultValue={storySustainability.image}
                                        required
                                    />
                                    <InputError
                                        message={
                                            errors['story_sustainability.image']
                                        }
                                    />
                                </div>
                            </section>

                            <section className="space-y-4">
                                <h3 className="font-medium">Números</h3>
                                <RepeaterField
                                    name="stats"
                                    addLabel="Adicionar número"
                                    defaultValue={stats}
                                    fields={[
                                        { name: 'number', label: 'Número' },
                                        { name: 'label', label: 'Legenda' },
                                    ]}
                                />
                                <InputError message={errors.stats} />
                            </section>

                            <section className="space-y-4">
                                <h3 className="font-medium">Valores</h3>
                                <RepeaterField
                                    name="values"
                                    addLabel="Adicionar valor"
                                    defaultValue={values}
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
                                <InputError message={errors.values} />
                            </section>

                            <section className="space-y-4">
                                <h3 className="font-medium">Depoimento</h3>
                                <div className="grid gap-2">
                                    <Label htmlFor="testimonial.quote">
                                        Depoimento
                                    </Label>
                                    <Textarea
                                        id="testimonial.quote"
                                        name="testimonial[quote]"
                                        defaultValue={testimonial.quote}
                                        required
                                    />
                                    <InputError
                                        message={errors['testimonial.quote']}
                                    />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="testimonial.author">
                                        Autor
                                    </Label>
                                    <Input
                                        id="testimonial.author"
                                        name="testimonial[author]"
                                        defaultValue={testimonial.author}
                                        required
                                    />
                                    <InputError
                                        message={errors['testimonial.author']}
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

BrandPageContent.layout = {
    breadcrumbs: [{ title: 'Conteúdo da página Marca', href: brand() }],
};
