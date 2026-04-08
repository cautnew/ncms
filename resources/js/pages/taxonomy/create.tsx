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

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Taxonomies', href: '/taxonomy' },
    { title: 'New', href: '/taxonomy/create' },
];

export default function TaxonomyCreate() {
    const { locales } = usePage<SharedData>().props as any;
    const availableLocales: Array<{ code: string; label: string }> = locales?.available ?? [
        { code: 'en', label: 'English' },
    ];
    const defaultLocale: string = locales?.default ?? availableLocales[0]?.code ?? 'en';

    const [slug, setSlug] = useState('');
    const [primaryLocale, setPrimaryLocale] = useState<string>(defaultLocale);
    /** Initial locale only — other languages on edit */
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
            await axios.post('/api/admin/taxonomies', {
                slug: slug || undefined,
                primary_locale: primaryLocale,
                translations: {
                    [primaryLocale]: { name, description: description || '' },
                },
            });
            window.location.href = '/taxonomy';
        } catch (err: any) {
            if (err.response?.data?.errors) {
                setErrors(err.response.data.errors);
            } else {
                alert(err.response?.data?.message ?? 'Could not create taxonomy.');
            }
        } finally {
            setProcessing(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="New taxonomy" />
            <TaxonomyLayout>
                <div className="space-y-6 max-w-xl">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="icon" asChild>
                            <Link href="/taxonomy">
                                <ArrowLeft className="h-4 w-4" />
                            </Link>
                        </Button>
                        <div>
                            <h2 className="text-xl font-semibold">New taxonomy</h2>
                            <p className="text-sm text-muted-foreground">Create a categorization vocabulary.</p>
                        </div>
                    </div>

                    <form onSubmit={submit} className="space-y-6 bg-card border rounded-lg p-6">
                        <div className="space-y-1.5">
                            <Label htmlFor="slug">
                                Slug{' '}
                                <span className="text-muted-foreground text-xs">(optional — auto-generated)</span>
                            </Label>
                            <Input
                                id="slug"
                                value={slug}
                                onChange={(e) => setSlug(e.target.value)}
                                placeholder="e.g. article-categories"
                            />
                            {errors['slug'] && <p className="text-xs text-destructive">{errors['slug']}</p>}
                        </div>

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
                                Translations for other languages can be added when editing the taxonomy.
                            </p>
                        </div>

                        <div className="space-y-1.5">
                            <Label htmlFor="name">Name</Label>
                            <Input
                                id="name"
                                value={name}
                                onChange={(e) => setName(e.target.value)}
                                placeholder="Taxonomy name"
                                required
                            />
                            {errors[`translations.${primaryLocale}.name`] && (
                                <p className="text-xs text-destructive">{errors[`translations.${primaryLocale}.name`]}</p>
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
                                <Link href="/taxonomy">Cancel</Link>
                            </Button>
                            <Button type="submit" disabled={processing}>
                                <PlusCircle className="h-4 w-4 mr-2" />
                                {processing ? 'Creating…' : 'Create taxonomy'}
                            </Button>
                        </div>
                    </form>
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
