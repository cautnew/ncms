import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';
import { Tag, PlusCircle } from 'lucide-react';

type Taxonomy = { slug: string; name: string };

type Props = PropsWithChildren<{ taxonomies?: Taxonomy[] }>;

export default function TaxonomiesLayout({ children, taxonomies = [] }: Props) {
    if (typeof window === 'undefined') return null;

    const currentPath = window.location.pathname;

    return (
        <div className="px-4 py-6">
            <Heading title="Taxonomies" description="Manage categorization vocabularies." />

            <div className="flex flex-col space-y-8 lg:flex-row lg:space-y-0 lg:space-x-12">
                {/* Sidebar */}
                <aside className="w-full max-w-xl lg:w-52 shrink-0">
                    <nav className="flex flex-col space-y-1">
                        {/* Root link */}
                        <Button
                            size="sm"
                            variant="ghost"
                            asChild
                            className={cn('w-full justify-start', { 'bg-muted': currentPath === '/taxonomy' })}
                        >
                            <Link href="/taxonomy" prefetch>
                                <Tag className="h-3.5 w-3.5 mr-2 opacity-60" />
                                All taxonomies
                            </Link>
                        </Button>

                        {/* Dynamic taxonomy links */}
                        {taxonomies.length > 0 && (
                            <>
                                <Separator className="my-2" />
                                <p className="text-xs text-muted-foreground px-2 py-1 uppercase tracking-wider font-semibold">
                                    Vocabularies
                                </p>
                                {taxonomies.map((tax) => (
                                    <Button
                                        key={tax.slug}
                                        size="sm"
                                        variant="ghost"
                                        asChild
                                        className={cn('w-full justify-start', {
                                            'bg-muted': currentPath.startsWith(`/taxonomy/${tax.slug}`),
                                        })}
                                    >
                                        <Link href={`/taxonomy/${tax.slug}`} prefetch>
                                            {tax.name}
                                        </Link>
                                    </Button>
                                ))}
                            </>
                        )}

                        <Separator className="my-2" />

                        {/* Create new */}
                        <Button size="sm" variant="ghost" asChild className="w-full justify-start text-primary">
                            <Link href="/taxonomy/create">
                                <PlusCircle className="h-3.5 w-3.5 mr-2" />
                                New taxonomy
                            </Link>
                        </Button>
                    </nav>
                </aside>

                <Separator className="my-6 md:hidden" />

                {/* Main content */}
                <div className="flex-1 min-w-0">
                    <section className="space-y-12">{children}</section>
                </div>
            </div>
        </div>
    );
}
