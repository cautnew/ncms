import { Head, Link } from '@inertiajs/react';
import { PlusCircle, Edit2, Trash2, Clock, ChevronRight } from 'lucide-react';

import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';

type Term = {
    id: string;
    slug: string;
    name: string;
    description: string | null;
    rank: number;
    parent_id: string | null;
    children: Term[];
};

type Taxonomy = {
    id: string;
    slug: string;
    name: string;
    description: string | null;
    translations: Record<string, { name: string; description: string }>;
    terms: Term[];
};

function TermRow({ term, taxonomySlug, depth = 0 }: { term: Term; taxonomySlug: string; depth?: number }) {
    return (
        <>
            <div
                className="flex items-center justify-between px-5 py-3 hover:bg-muted/40 transition-colors"
                style={{ paddingLeft: `${1.25 + depth * 1.5}rem` }}
            >
                <div className="flex items-center gap-2">
                    {depth > 0 && <ChevronRight className="h-3.5 w-3.5 text-muted-foreground" />}
                    <div>
                        <span className="font-medium text-sm">{term.name}</span>
                        <Badge variant="outline" className="ml-2 text-xs">{term.slug}</Badge>
                        {term.description && (
                            <p className="text-xs text-muted-foreground mt-0.5">{term.description}</p>
                        )}
                    </div>
                </div>
                <div className="flex items-center gap-1 shrink-0">
                    <Button variant="ghost" size="icon" asChild title="Edit">
                        <Link href={`/taxonomy/${taxonomySlug}/terms/${term.slug}/edit`}>
                            <Edit2 className="h-3.5 w-3.5" />
                        </Link>
                    </Button>
                    <Button variant="ghost" size="icon" asChild title="History">
                        <Link href={`/taxonomy/${taxonomySlug}/terms/${term.slug}/history`}>
                            <Clock className="h-3.5 w-3.5" />
                        </Link>
                    </Button>
                    <Button variant="ghost" size="icon" asChild title="Delete" className="text-destructive hover:text-destructive">
                        <Link href={`/taxonomy/${taxonomySlug}/terms/${term.slug}/delete`}>
                            <Trash2 className="h-3.5 w-3.5" />
                        </Link>
                    </Button>
                </div>
            </div>
            {term.children?.map(child => (
                <TermRow key={child.id} term={child} taxonomySlug={taxonomySlug} depth={depth + 1} />
            ))}
        </>
    );
}

export default function TaxonomyShow({ taxonomy }: { taxonomy: Taxonomy }) {
    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Taxonomies', href: '/taxonomy' },
        { title: taxonomy.name, href: `/taxonomy/${taxonomy.slug}` },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={taxonomy.name} />
            <TaxonomyLayout>
                <div className="space-y-6">
                    {/* Header */}
                    <div className="flex items-start justify-between">
                        <div>
                            <h2 className="text-xl font-semibold">{taxonomy.name}</h2>
                            <p className="text-sm text-muted-foreground mt-0.5">
                                Slug: <code>{taxonomy.slug}</code>
                            </p>
                            {taxonomy.description && (
                                <p className="text-sm text-muted-foreground mt-1">{taxonomy.description}</p>
                            )}
                        </div>
                        <div className="flex gap-2">
                            <Button variant="outline" asChild>
                                <Link href={`/taxonomy/${taxonomy.slug}/edit`}>
                                    <Edit2 className="h-4 w-4 mr-1.5" />Edit
                                </Link>
                            </Button>
                            <Button variant="outline" asChild>
                                <Link href={`/taxonomy/${taxonomy.slug}/history`}>
                                    <Clock className="h-4 w-4 mr-1.5" />History
                                </Link>
                            </Button>
                            <Button variant="destructive" size="sm" asChild>
                                <Link href={`/taxonomy/${taxonomy.slug}/delete`}>
                                    <Trash2 className="h-4 w-4 mr-1.5" />Delete
                                </Link>
                            </Button>
                        </div>
                    </div>

                    {/* Terms */}
                    <div className="space-y-2">
                        <div className="flex items-center justify-between">
                            <h3 className="text-base font-medium">Terms</h3>
                            <Button size="sm" asChild>
                                <Link href={`/taxonomy/${taxonomy.slug}/terms/create`}>
                                    <PlusCircle className="h-4 w-4 mr-1.5" />New term
                                </Link>
                            </Button>
                        </div>

                        {taxonomy.terms.length === 0 ? (
                            <div className="text-center py-10 text-muted-foreground border rounded-lg">
                                <p>No terms yet.</p>
                                <Button className="mt-3" size="sm" asChild>
                                    <Link href={`/taxonomy/${taxonomy.slug}/terms/create`}>Add term</Link>
                                </Button>
                            </div>
                        ) : (
                            <div className="border rounded-lg bg-card divide-y">
                                {taxonomy.terms.map(term => (
                                    <TermRow key={term.id} term={term} taxonomySlug={taxonomy.slug} />
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
