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
    taxonomy: {
        id: string;
        slug: string;
        translations: Record<string, { name: string; description: string }>;
    };
};

export default function TaxonomyEdit({ taxonomy }: Props) {
    const { locales } = usePage<SharedData>().props as any;
    const availableLocales: Array<{ code: string; label: string }> = locales?.available ?? [
        { code: 'en', label: 'English' },
    ];
    const defaultLocale: string = locales?.default ?? availableLocales[0]?.code ?? 'en';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Taxonomies', href: '/taxonomy' },
        { title: taxonomy.slug, href: `/taxonomy/${taxonomy.slug}` },
        { title: 'Edit', href: '#' },
    ];

    const [slug, setSlug] = useState(taxonomy.slug);
    const [translations, setTranslations] = useState<Record<string, { name: string; description: string }>>(() => {
        const base: Record<string, { name: string; description: string }> = {};
        for (const l of availableLocales) base[l.code] = { name: '', description: '' };
        return { ...base, ...(taxonomy.translations ?? {}) };
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
            await axios.put(`/api/admin/taxonomies/${taxonomy.slug}`, { slug, translations });
            window.location.href = '/taxonomy';
        } catch (err: any) {
            if (err.response?.data?.errors) setErrors(err.response.data.errors);
            else alert(err.response?.data?.message ?? 'Could not save.');
        } finally {
            setProcessing(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit: ${taxonomy.slug}`} />
            <TaxonomyLayout>
                <div className="space-y-6 max-w-3xl">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="icon" asChild>
                            <Link href={`/taxonomy/${taxonomy.slug}`}>
                                <ArrowLeft className="h-4 w-4" />
                            </Link>
                        </Button>
                        <div>
                            <h2 className="text-xl font-semibold">Edit taxonomy</h2>
                            <p className="text-sm text-muted-foreground">
                                Slug: <code>{taxonomy.slug}</code>
                            </p>
                        </div>
                    </div>

                    <form onSubmit={submit} className="space-y-8 bg-card border rounded-lg p-6">
                        <div className="space-y-1.5">
                            <Label htmlFor="slug">Slug</Label>
                            <Input id="slug" value={slug} onChange={(e) => setSlug(e.target.value)} />
                            {errors['slug'] && <p className="text-xs text-destructive">{errors['slug']}</p>}
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
