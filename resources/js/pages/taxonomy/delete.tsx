import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import axios from 'axios';
import { AlertTriangle, ArrowLeft, Trash2 } from 'lucide-react';

import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { Button } from '@/components/ui/button';
import { type BreadcrumbItem } from '@/types';

type Props = {
    taxonomy: {
        id: string;
        slug: string;
        name: string;
        terms_count: number;
    };
};

export default function TaxonomyDelete({ taxonomy }: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Taxonomies', href: '/taxonomy' },
        { title: taxonomy.name, href: `/taxonomy/${taxonomy.slug}` },
        { title: 'Delete', href: '#' },
    ];

    const [processing, setProcessing] = useState(false);

    const handleDelete = async () => {
        setProcessing(true);
        try {
            await axios.delete(`/api/admin/taxonomies/${taxonomy.slug}`);
            window.location.href = '/taxonomy';
        } catch (err: any) {
            alert(err.response?.data?.message ?? 'Could not delete.');
            setProcessing(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Delete: ${taxonomy.name}`} />
            <TaxonomyLayout>
                <div className="space-y-6 max-w-lg">
                    <div className="flex items-center gap-3">
                        <Button variant="ghost" size="icon" asChild>
                            <Link href={`/taxonomy/${taxonomy.slug}`}><ArrowLeft className="h-4 w-4" /></Link>
                        </Button>
                        <h2 className="text-xl font-semibold">Confirm deletion</h2>
                    </div>

                    <div className="border border-destructive/40 bg-destructive/5 rounded-lg p-6 space-y-4">
                        <div className="flex items-start gap-3">
                            <AlertTriangle className="h-6 w-6 text-destructive shrink-0 mt-0.5" />
                            <div>
                                <p className="font-semibold text-destructive">Warning — this deletion cannot be undone</p>
                                <p className="text-sm text-muted-foreground mt-1">
                                    You are about to delete the taxonomy <strong>"{taxonomy.name}"</strong> (
                                    <code>{taxonomy.slug}</code>).
                                </p>
                                {taxonomy.terms_count > 0 && (
                                    <p className="text-sm text-orange-600 mt-2">
                                        This taxonomy has <strong>{taxonomy.terms_count} term(s)</strong> that will also be deleted.
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
                                {processing ? 'Deleting…' : 'Delete taxonomy'}
                            </Button>
                        </div>
                    </div>
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
