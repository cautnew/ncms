import { Head, useForm } from '@inertiajs/react';
import { useMemo } from 'react';

import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';

type LocaleItem = { code: string; label: string };

type Props = {
    available: LocaleItem[];
    default: string;
};

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Languages', href: '/settings/locales' }];

export default function SettingsLocales({ available, default: defaultLocale }: Props) {
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm<{
        available: LocaleItem[];
        default: string;
    }>({
        available: available?.length ? available : [{ code: 'en', label: 'English' }],
        default: defaultLocale || 'en',
    });

    const localeOptions = useMemo(() => data.available.map((l) => l.code), [data.available]);

    const addLocale = () => setData('available', [...data.available, { code: '', label: '' }]);

    const removeLocale = (idx: number) => {
        const next = data.available.filter((_, i) => i !== idx);
        setData('available', next.length ? next : [{ code: 'en', label: 'English' }]);
        if (!next.find((l) => l.code === data.default)) {
            setData('default', next[0]?.code || 'en');
        }
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route('settings.locales.update'), { preserveScroll: true });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Languages" />
            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall
                        title="Languages"
                        description="Choose which languages are available for content and which is the default."
                    />

                    <form onSubmit={submit} className="space-y-6">
                        <div className="space-y-1.5">
                            <Label htmlFor="default">Default language</Label>
                            <select
                                id="default"
                                className="h-10 w-full rounded-md border bg-background px-3 text-sm"
                                value={data.default}
                                onChange={(e) => setData('default', e.target.value)}
                            >
                                {localeOptions.map((code) => (
                                    <option key={code} value={code}>
                                        {code}
                                    </option>
                                ))}
                            </select>
                            <InputError message={errors['default']} />
                        </div>

                        <div className="space-y-3">
                            <div className="flex items-center justify-between">
                                <p className="text-sm font-medium">Available languages</p>
                                <Button type="button" variant="outline" onClick={addLocale}>
                                    Add language
                                </Button>
                            </div>

                            <div className="space-y-3">
                                {data.available.map((loc, idx) => (
                                    <div key={idx} className="grid grid-cols-1 sm:grid-cols-5 gap-3 border rounded-md p-4">
                                        <div className="sm:col-span-2 space-y-1.5">
                                            <Label>Code</Label>
                                            <Input
                                                value={loc.code}
                                                onChange={(e) => {
                                                    const next = [...data.available];
                                                    next[idx] = { ...next[idx], code: e.target.value.trim() };
                                                    setData('available', next);
                                                }}
                                                placeholder="ex: pt, en, es"
                                            />
                                        </div>
                                        <div className="sm:col-span-2 space-y-1.5">
                                            <Label>Label</Label>
                                            <Input
                                                value={loc.label ?? ''}
                                                onChange={(e) => {
                                                    const next = [...data.available];
                                                    next[idx] = { ...next[idx], label: e.target.value };
                                                    setData('available', next);
                                                }}
                                                placeholder="e.g. Portuguese"
                                            />
                                        </div>
                                        <div className="sm:col-span-1 flex items-end justify-end">
                                            <Button type="button" variant="destructive" onClick={() => removeLocale(idx)}>
                                                Remove
                                            </Button>
                                        </div>
                                    </div>
                                ))}
                            </div>

                            <InputError message={errors['available']} />
                        </div>

                        <div className="flex items-center gap-4">
                            <Button disabled={processing}>Save</Button>
                            {recentlySuccessful && <p className="text-sm text-muted-foreground">Saved.</p>}
                        </div>
                    </form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}

