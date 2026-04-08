import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { useMemo, useState } from 'react';

export type LocaleOption = { code: string; label: string };

type Props = {
    locales: LocaleOption[];
    translations: Record<string, { name: string; description: string }>;
    onChange: (locale: string, field: 'name' | 'description', value: string) => void;
    errors: Record<string, string>;
    /** First tab open (e.g. site default language) */
    defaultActiveCode?: string;
    /** Section title (accessibility) */
    sectionLabel?: string;
};

/**
 * Side navigation by language (same visual pattern as Settings) to edit name/description per locale.
 */
export default function LocaleTranslationTabs({
    locales,
    translations,
    onChange,
    errors,
    defaultActiveCode,
    sectionLabel = 'Translations',
}: Props) {
    const initial = useMemo(() => {
        const codes = locales.map((l) => l.code);
        const preferred = defaultActiveCode && codes.includes(defaultActiveCode) ? defaultActiveCode : codes[0];
        return preferred ?? 'en';
    }, [locales, defaultActiveCode]);

    const [active, setActive] = useState(initial);

    const activeLabel = locales.find((l) => l.code === active)?.label ?? active;

    return (
        <div className="space-y-3">
            <p className="text-sm font-medium">{sectionLabel}</p>
            <div className="flex flex-col space-y-6 lg:flex-row lg:space-y-0 lg:space-x-10">
                <aside className="w-full shrink-0 lg:w-48">
                    <nav className="flex flex-col space-y-1" aria-label={sectionLabel}>
                        {locales.map(({ code, label }) => (
                            <Button
                                key={code}
                                type="button"
                                size="sm"
                                variant="ghost"
                                className={cn('w-full justify-start', active === code && 'bg-muted')}
                                onClick={() => setActive(code)}
                            >
                                <span className="truncate">
                                    {label}{' '}
                                    <span className="text-muted-foreground font-normal">({code})</span>
                                </span>
                            </Button>
                        ))}
                    </nav>
                </aside>

                <Separator className="lg:hidden" />

                <div className="min-w-0 flex-1 space-y-4 rounded-lg border bg-card/30 p-4">
                    <p className="text-xs font-semibold text-muted-foreground uppercase tracking-wider">{activeLabel}</p>
                    <div className="space-y-1.5">
                        <Label htmlFor={`name-${active}`}>Name</Label>
                        <Input
                            id={`name-${active}`}
                            value={translations[active]?.name ?? ''}
                            onChange={(e) => onChange(active, 'name', e.target.value)}
                            placeholder="Name in this language"
                        />
                        {errors[`translations.${active}.name`] && (
                            <p className="text-xs text-destructive">{errors[`translations.${active}.name`]}</p>
                        )}
                    </div>
                    <div className="space-y-1.5">
                        <Label htmlFor={`desc-${active}`}>Description</Label>
                        <Input
                            id={`desc-${active}`}
                            value={translations[active]?.description ?? ''}
                            onChange={(e) => onChange(active, 'description', e.target.value)}
                            placeholder="Optional"
                        />
                    </div>
                </div>
            </div>
        </div>
    );
}
