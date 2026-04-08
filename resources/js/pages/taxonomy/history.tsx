import { Head, Link } from '@inertiajs/react';
import { ArrowLeft, Clock } from 'lucide-react';

import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';

type Log = {
    id: number;
    action: 'created' | 'updated' | 'deleted' | 'restored';
    old_values: Record<string, any> | null;
    new_values: Record<string, any> | null;
    user: { name: string } | null;
    created_at: string;
};

const actionLabel: Record<string, { label: string; variant: 'default' | 'secondary' | 'destructive' | 'outline' }> = {
    created:  { label: 'Created',   variant: 'default' },
    updated:  { label: 'Updated',   variant: 'secondary' },
    deleted:  { label: 'Deleted',   variant: 'destructive' },
    restored: { label: 'Restored',  variant: 'outline' },
};

type Props = {
    taxonomy: { slug: string; name: string };
    logs: Log[];
};

export default function TaxonomyHistory({ taxonomy, logs }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Taxonomies', href: '/taxonomy' },
        { title: taxonomy.name, href: `/taxonomy/${taxonomy.slug}` },
        { title: 'History', href: '#' },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`History: ${taxonomy.name}`} />
            <TaxonomyLayout>
                <div className="space-y-6 max-w-2xl">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="icon" asChild>
                            <Link href={`/taxonomy/${taxonomy.slug}`}><ArrowLeft className="h-4 w-4" /></Link>
                        </Button>
                        <div>
                            <h2 className="text-xl font-semibold flex items-center gap-2">
                                <Clock className="h-5 w-5" />
                                Audit history
                            </h2>
                            <p className="text-sm text-muted-foreground">Taxonomy: {taxonomy.name}</p>
                        </div>
                    </div>

                    {logs.length === 0 ? (
                        <p className="text-muted-foreground text-sm">No audit records.</p>
                    ) : (
                        <div className="space-y-3">
                            {logs.map(log => {
                                const meta = actionLabel[log.action] ?? { label: log.action, variant: 'outline' as const };
                                return (
                                    <div key={log.id} className="border rounded-lg p-4 space-y-3 bg-card">
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-2">
                                                <Badge variant={meta.variant}>{meta.label}</Badge>
                                                <span className="text-sm text-muted-foreground">
                                                    by {log.user?.name ?? 'System'}
                                                </span>
                                            </div>
                                            <span className="text-xs text-muted-foreground">
                                                {new Date(log.created_at).toLocaleString('en-US')}
                                            </span>
                                        </div>

                                        {(log.old_values || log.new_values) && (
                                            <div className="grid grid-cols-2 gap-3 text-xs">
                                                {log.old_values && (
                                                    <div>
                                                        <p className="font-semibold mb-1 text-muted-foreground">Before</p>
                                                        <pre className="bg-muted rounded p-2 overflow-x-auto whitespace-pre-wrap">
                                                            {JSON.stringify(log.old_values, null, 2)}
                                                        </pre>
                                                    </div>
                                                )}
                                                {log.new_values && (
                                                    <div>
                                                        <p className="font-semibold mb-1 text-muted-foreground">After</p>
                                                        <pre className="bg-muted rounded p-2 overflow-x-auto whitespace-pre-wrap">
                                                            {JSON.stringify(log.new_values, null, 2)}
                                                        </pre>
                                                    </div>
                                                )}
                                            </div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
