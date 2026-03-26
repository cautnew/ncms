import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent, CardFooter } from '@/components/ui/card';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Page Typess',
        href: '/page-types',
    },
];

export default function Index({ page_types }: { page_types: any[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Page Types" />
            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto rounded-xl p-4">
                <div className="flex justify-between items-center">
                    <h3 className="text-sm font-semibold">Check the list of all of your page types.</h3>
                    <div className="flex gap-2">
                        <Button asChild>
                            <Link href="/page-types/create">Create Page Type</Link>
                        </Button>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {page_types.map((type) => (
                        <Card key={type.id} className="relative flex flex-col hover:border-primary transition-colors cursor-pointer group">
                            <Link href={`/page-types/${type.id}`} className="absolute inset-0 z-0" aria-label={`View ${type.name}`} />
                            <CardHeader>
                                <CardTitle className="flex justify-between items-start">
                                    <span>{type.name}</span>
                                    <span className="text-xs bg-muted px-2 py-1 rounded-md text-muted-foreground">{type.version}</span>
                                </CardTitle>
                                <CardDescription>{type.slug}</CardDescription>
                            </CardHeader>
                            <CardContent className="flex-1">
                                <p className="text-sm text-muted-foreground line-clamp-3">
                                    {type.description || 'No description provided.'}
                                </p>
                            </CardContent>
                            <CardFooter className="flex justify-between items-center z-10">
                                <span className={`text-xs px-2 py-1 rounded-full ${type.active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'}`}>
                                    {type.active ? 'Active' : 'Inactive'}
                                </span>
                                <Button asChild variant="outline" size="sm">
                                    <Link href={`/page-types/${type.id}/edit`}>Edit</Link>
                                </Button>
                            </CardFooter>
                        </Card>
                    ))}
                    {page_types.length === 0 && (
                        <div className="col-span-full py-12 text-center text-muted-foreground bg-muted/30 rounded-xl border border-dashed">
                            No page types found. Create one to get started.
                        </div>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
