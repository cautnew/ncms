import { Head, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import axios from 'axios';
import { ArrowLeft, PlusCircle } from 'lucide-react';

import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem, type SharedData } from '@/types';

type Props = {
    taxonomy: { slug: string; name: string };
    terms: { slug: string; name: string }[];
};

export default function TermCreate({ taxonomy, terms }: Props) {
    const { locales } = usePage<SharedData>().props as any;
    const availableLocales: Array<{ code: string; label: string }> = locales?.available ?? [
        { code: 'en', label: 'English' },
    ];
    const defaultLocale: string = locales?.default ?? availableLocales[0]?.code ?? 'en';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Taxonomies', href: '/taxonomy' },
        { title: taxonomy.name, href: `/taxonomy/${taxonomy.slug}` },
        { title: 'New term', href: '#' },
    ];

    const [slug, setSlug] = useState('');
    const [rank, setRank] = useState(0);
    const [parentSlug, setParentSlug] = useState('');
    const [primaryLocale, setPrimaryLocale] = useState<string>(defaultLocale);
    const [name, setName] = useState('');
    const [description, setDescription] = useState('');
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [processing, setProcessing] = useState(false);

    const onPrimaryChange = (code: string) => {
        setPrimaryLocale(code);
        setName('');
        setDescription('');
        setErrors({});
    };

    const submit = async (e: React.FormEvent) => {
        e.preventDefault();
        setErrors({});
        setProcessing(true);
        try {
            await axios.post(`/api/admin/taxonomies/${taxonomy.slug}/terms`, {
                slug: slug || undefined,
                rank,
                parent_slug: parentSlug || undefined,
                primary_locale: primaryLocale,
                translations: {
                    [primaryLocale]: { name, description: description || '' },
                },
            });
            window.location.href = `/taxonomy/${taxonomy.slug}`;
        } catch (err: any) {
            if (err.response?.data?.errors) setErrors(err.response.data.errors);
            else alert(err.response?.data?.message ?? 'Could not create term.');
        } finally {
            setProcessing(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="New term" />
            <TaxonomyLayout>
                <div className="space-y-6 max-w-xl">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="icon" asChild>
                            <Link href={`/taxonomy/${taxonomy.slug}`}>
                                <ArrowLeft className="h-4 w-4" />
                            </Link>
                        </Button>
                        <div>
                            <h2 className="text-xl font-semibold">New term</h2>
                            <p className="text-sm text-muted-foreground">Taxonomy: {taxonomy.name}</p>
                        </div>
                    </div>

                    <form onSubmit={submit} className="space-y-6 bg-card border rounded-lg p-6">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="slug">
                                    Slug <span className="text-muted-foreground text-xs">(optional)</span>
                                </Label>
                                <Input id="slug" value={slug} onChange={(e) => setSlug(e.target.value)} placeholder="auto-generated" />
                            </div>
                            <div className="space-y-1.5">
                                <Label htmlFor="rank">Rank (order)</Label>
                                <Input
                                    id="rank"
                                    type="number"
                                    value={rank}
                                    onChange={(e) => setRank(Number(e.target.value))}
                                />
                            </div>
                        </div>

                        {terms.length > 0 && (
                            <div className="space-y-1.5">
                                <Label htmlFor="parent">Parent term (optional)</Label>
                                <select
                                    id="parent"
                                    className="w-full border rounded px-3 py-2 text-sm bg-background"
                                    value={parentSlug}
                                    onChange={(e) => setParentSlug(e.target.value)}
                                >
                                    <option value="">— none (root) —</option>
                                    {terms.map((t) => (
                                        <option key={t.slug} value={t.slug}>
                                            {t.name} ({t.slug})
                                        </option>
                                    ))}
                                </select>
                            </div>
                        )}

                        <div className="space-y-1.5">
                            <Label htmlFor="primaryLocale">Initial language</Label>
                            <select
                                id="primaryLocale"
                                className="h-10 w-full rounded-md border bg-background px-3 text-sm"
                                value={primaryLocale}
                                onChange={(e) => onPrimaryChange(e.target.value)}
                            >
                                {availableLocales.map(({ code, label }) => (
                                    <option key={code} value={code}>
                                        {label} ({code})
                                    </option>
                                ))}
                            </select>
                            {errors['primary_locale'] && (
                                <p className="text-xs text-destructive">{errors['primary_locale']}</p>
                            )}
                            <p className="text-xs text-muted-foreground">
                                Translations for other languages can be added when editing the term.
                            </p>
                        </div>

                        <div className="space-y-1.5">
                            <Label htmlFor="name">Name</Label>
                            <Input
                                id="name"
                                value={name}
                                onChange={(e) => setName(e.target.value)}
                                placeholder="Term name"
                                required
                            />
                            {errors[`translations.${primaryLocale}.name`] && (
                                <p className="text-xs text-destructive">
                                    {errors[`translations.${primaryLocale}.name`]}
                                </p>
                            )}
                        </div>

                        <div className="space-y-1.5">
                            <Label htmlFor="desc">Description</Label>
                            <Input
                                id="desc"
                                value={description}
                                onChange={(e) => setDescription(e.target.value)}
                                placeholder="Optional"
                            />
                        </div>

                        <div className="flex justify-end gap-3">
                            <Button variant="outline" asChild>
                                <Link href={`/taxonomy/${taxonomy.slug}`}>Cancel</Link>
                            </Button>
                            <Button type="submit" disabled={processing}>
                                <PlusCircle className="h-4 w-4 mr-2" />
                                {processing ? 'Creating…' : 'Create term'}
                            </Button>
                        </div>
                    </form>
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
