import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import axios from 'axios';
import { AlertTriangle, ArrowLeft, Trash2 } from 'lucide-react';

import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';

type Props = {
    taxonomy: { slug: string; name: string };
    term: { slug: string; name: string; children_count: number };
};

export default function TermDelete({ taxonomy, term }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Taxonomies', href: '/taxonomy' },
        { title: taxonomy.name, href: `/taxonomy/${taxonomy.slug}` },
        { title: `Delete: ${term.name}`, href: '#' },
    ];

    const [processing, setProcessing] = useState(false);

    const handleDelete = async () => {
        setProcessing(true);
        try {
            await axios.delete(`/api/admin/taxonomies/${taxonomy.slug}/terms/${term.slug}`);
            window.location.href = `/taxonomy/${taxonomy.slug}`;
        } catch (err: any) {
            alert(err.response?.data?.message ?? 'Could not delete.');
            setProcessing(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Delete term: ${term.name}`} />
            <TaxonomyLayout>
                <div className="space-y-6 max-w-lg">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="icon" asChild>
                            <Link href={`/taxonomy/${taxonomy.slug}`}><ArrowLeft className="h-4 w-4" /></Link>
                        </Button>
                        <h2 className="text-xl font-semibold">Confirm term deletion</h2>
                    </div>

                    <div className="border border-destructive/40 bg-destructive/5 rounded-lg p-6 space-y-4">
                        <div className="flex items-start gap-3">
                            <AlertTriangle className="h-6 w-6 text-destructive shrink-0 mt-0.5" />
                            <div className="space-y-1">
                                <p className="font-semibold text-destructive">Warning</p>
                                <p className="text-sm text-muted-foreground">
                                    You are about to delete the term <strong>"{term.name}"</strong> (<code>{term.slug}</code>)
                                    from taxonomy <strong>{taxonomy.name}</strong>.
                                </p>
                                {term.children_count > 0 && (
                                    <p className="text-sm text-orange-600">
                                        This term has <strong>{term.children_count} child term(s)</strong> that will be unlinked (parent_id will be null).
                                    </p>
                                )}
                                <p className="text-sm text-muted-foreground mt-2">
                                    Deletion is a <em>soft delete</em> — audit history is preserved.
                                </p>
                            </div>
                        </div>

                        <div className="flex justify-end gap-3 pt-2">
                            <Button variant="outline" asChild>
                                <Link href={`/taxonomy/${taxonomy.slug}`}>Cancel</Link>
                            </Button>
                            <Button variant="destructive" onClick={handleDelete} disabled={processing}>
                                <Trash2 className="h-4 w-4 mr-2" />
                                {processing ? 'Deleting…' : 'Delete term'}
                            </Button>
                        </div>
                    </div>
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
