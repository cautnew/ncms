import { Head, Link, usePage } from '@inertiajs/react';
import { useState } from 'react';
import axios from 'axios';
import { ArrowLeft, Save } from 'lucide-react';

import LocaleTranslationTabs from '@/components/taxonomy/locale-translation-tabs';
import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem, type SharedData } from '@/types';

type Props = {
    taxonomy: { slug: string; name: string };
    term: {
        id: string;
        slug: string;
        rank: number;
        parent_id: string | null;
        translations: Record<string, { name: string; description: string }>;
    };
};

export default function TermEdit({ taxonomy, term }: Props) {
    const { locales } = usePage<SharedData>().props as any;
    const availableLocales: Array<{ code: string; label: string }> = locales?.available ?? [
        { code: 'en', label: 'English' },
    ];
    const defaultLocale: string = locales?.default ?? availableLocales[0]?.code ?? 'en';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Taxonomies', href: '/taxonomy' },
        { title: taxonomy.name, href: `/taxonomy/${taxonomy.slug}` },
        { title: term.slug, href: `/taxonomy/${taxonomy.slug}/terms/${term.slug}/edit` },
    ];

    const [slug, setSlug] = useState(term.slug);
    const [rank, setRank] = useState(term.rank);
    const [translations, setTranslations] = useState<Record<string, { name: string; description: string }>>(() => {
        const base: Record<string, { name: string; description: string }> = {};
        for (const l of availableLocales) base[l.code] = { name: '', description: '' };
        return { ...base, ...(term.translations ?? {}) };
    });
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [processing, setProcessing] = useState(false);

    const setTranslation = (locale: string, field: 'name' | 'description', value: string) => {
        setTranslations((prev) => ({
            ...prev,
            [locale]: { ...(prev[locale] ?? { name: '', description: '' }), [field]: value },
        }));
    };

    const submit = async (e: React.FormEvent) => {
        e.preventDefault();
        setErrors({});
        setProcessing(true);
        try {
            await axios.put(`/api/admin/taxonomies/${taxonomy.slug}/terms/${term.slug}`, { slug, rank, translations });
            window.location.href = `/taxonomy/${taxonomy.slug}`;
        } catch (err: any) {
            if (err.response?.data?.errors) setErrors(err.response.data.errors);
            else alert(err.response?.data?.message ?? 'Could not save.');
        } finally {
            setProcessing(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit term: ${term.slug}`} />
            <TaxonomyLayout>
                <div className="space-y-6 max-w-3xl">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="icon" asChild>
                            <Link href={`/taxonomy/${taxonomy.slug}`}>
                                <ArrowLeft className="h-4 w-4" />
                            </Link>
                        </Button>
                        <div>
                            <h2 className="text-xl font-semibold">Edit term</h2>
                            <p className="text-sm text-muted-foreground">
                                Taxonomy: {taxonomy.name} / <code>{term.slug}</code>
                            </p>
                        </div>
                    </div>

                    <form onSubmit={submit} className="space-y-8 bg-card border rounded-lg p-6">
                        <div className="grid grid-cols-2 gap-4">
                            <div className="space-y-1.5">
                                <Label htmlFor="slug">Slug</Label>
                                <Input id="slug" value={slug} onChange={(e) => setSlug(e.target.value)} />
                                {errors['slug'] && <p className="text-xs text-destructive">{errors['slug']}</p>}
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

                        <LocaleTranslationTabs
                            locales={availableLocales}
                            translations={translations}
                            onChange={setTranslation}
                            errors={errors}
                            defaultActiveCode={defaultLocale}
                            sectionLabel="Names by language"
                        />

                        <div className="flex justify-end gap-3">
                            <Button variant="outline" asChild>
                                <Link href={`/taxonomy/${taxonomy.slug}`}>Cancel</Link>
                            </Button>
                            <Button type="submit" disabled={processing}>
                                <Save className="h-4 w-4 mr-2" />
                                {processing ? 'Saving…' : 'Save changes'}
                            </Button>
                        </div>
                    </form>
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
