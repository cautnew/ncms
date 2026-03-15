import { Appearance, useAppearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';
import { LucideIcon, Monitor, Moon, Sun } from 'lucide-react';
import { HTMLAttributes } from 'react';

type AppearanceType = HTMLAttributes<HTMLDivElement> & {
    className?: string,
    noLabel?: boolean
};

export default function AppearanceToggleTab({ className = '', noLabel = false, ...props }: AppearanceType) {
    const { appearance, updateAppearance } = useAppearance();

    const tabs: { value: Appearance; icon: LucideIcon; label: string }[] = [
        { value: 'light', icon: Sun, label: 'Light' },
        { value: 'dark', icon: Moon, label: 'Dark' },
        { value: 'system', icon: Monitor, label: 'System' },
    ];

    return (
        <div className={cn('inline-flex gap-1 rounded-lg bg-neutral-100 p-1 dark:bg-neutral-800', noLabel ? 'justify-between' : '', className)} {...props}>
            {tabs.map(({ value, icon: Icon, label }) => (
                <button
                    key={value}
                    onClick={() => updateAppearance(value)}
                    className={cn(
                        'flex justify-center items-center rounded-md px-3.5 py-1.5 transition-colors',
                        appearance === value
                            ? 'bg-white shadow-xs dark:bg-neutral-700 dark:text-neutral-100'
                            : 'text-neutral-500 hover:bg-neutral-200/60 hover:text-black dark:text-neutral-400 dark:hover:bg-neutral-700/60',
                        noLabel ? 'flex-1' : '',
                    )}
                >
                    <Icon className={cn("h-4 w-4", !noLabel ? '-ml-1' : '')} />
                    {!noLabel && <span className="ml-1.5 text-sm">{label}</span>}
                </button>
            ))}
        </div>
    );
}
