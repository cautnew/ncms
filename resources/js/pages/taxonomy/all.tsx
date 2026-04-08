import { Head, Link, router } from '@inertiajs/react';
import { PlusCircle, Edit2, Trash2, Clock, Tag } from 'lucide-react';

import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Taxonomies', href: '/taxonomy' },
];

type Taxonomy = {
    id: string;
    slug: string;
    name: string;
    description: string | null;
    terms_count: number;
};

export default function TaxonomyIndex({ taxonomies = [] }: { taxonomies: Taxonomy[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Taxonomies" />
            <TaxonomyLayout taxonomies={taxonomies}>
                <div className="space-y-6">
                    {/* Header */}
                    <div className="flex items-center justify-between">
                        <div>
                            <h2 className="text-xl font-semibold">All taxonomies</h2>
                            <p className="text-sm text-muted-foreground mt-1">
                                Manage vocabularies such as categories, tags, and content types.
                            </p>
                        </div>
                        <Button asChild>
                            <Link href="/taxonomy/create">
                                <PlusCircle className="h-4 w-4 mr-2" />
                                New taxonomy
                            </Link>
                        </Button>
                    </div>

                    {/* List */}
                    {taxonomies.length === 0 ? (
                        <div className="text-center py-20 text-muted-foreground border rounded-lg">
                            <Tag className="mx-auto h-10 w-10 mb-3 opacity-30" />
                            <p className="text-lg font-medium">No taxonomies yet.</p>
                            <p className="text-sm mt-1">Create your first taxonomy to organize content.</p>
                            <Button className="mt-4" asChild>
                                <Link href="/taxonomy/create">Create taxonomy</Link>
                            </Button>
                        </div>
                    ) : (
                        <div className="divide-y border rounded-lg bg-card">
                            {taxonomies.map((tax) => (
                                <div
                                    key={tax.id}
                                    className="flex items-center justify-between px-5 py-4 hover:bg-muted/40 transition-colors"
                                >
                                    <div className="space-y-0.5">
                                        <Link
                                            href={`/taxonomy/${tax.slug}`}
                                            className="font-medium hover:underline"
                                        >
                                            {tax.name}
                                        </Link>
                                        <div className="flex items-center gap-2 mt-1">
                                            <Badge variant="secondary">{tax.slug}</Badge>
                                            <span className="text-xs text-muted-foreground">
                                                {tax.terms_count} {tax.terms_count === 1 ? 'term' : 'terms'}
                                            </span>
                                        </div>
                                        {tax.description && (
                                            <p className="text-sm text-muted-foreground mt-1">{tax.description}</p>
                                        )}
                                    </div>
                                    <div className="flex items-center gap-1 shrink-0 ml-4">
                                        <Button variant="ghost" size="icon" asChild title="Edit">
                                            <Link href={`/taxonomy/${tax.slug}/edit`}>
                                                <Edit2 className="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="icon" asChild title="History">
                                            <Link href={`/taxonomy/${tax.slug}/history`}>
                                                <Clock className="h-4 w-4" />
                                            </Link>
                                        </Button>
                                        <Button variant="ghost" size="icon" asChild title="Delete" className="text-destructive hover:text-destructive">
                                            <Link href={`/taxonomy/${tax.slug}/delete`}>
                                                <Trash2 className="h-4 w-4" />
                                            </Link>
                                        </Button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
