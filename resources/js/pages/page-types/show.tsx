import React, { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardDescription, CardContent } from '@/components/ui/card';
import axios from 'axios';

export default function Show({ page_type }: { page_type: any }) {
    const [pages, setPages] = useState<any[] | null>(null);
    const [loading, setLoading] = useState(false);

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Page Types', href: '/page-types' },
        { title: page_type.name, href: `/page-types/${page_type.id}` },
    ];

    const loadPages = async () => {
        setLoading(true);
        try {
            const response = await axios.get(`/page-types/${page_type.id}/pages`);
            setPages(response.data);
        } catch (error) {
            console.error("Failed to load pages", error);
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Page Type: ${page_type.name}`} />
            <div className="flex w-full max-w-5xl flex-1 flex-col gap-6 p-4">
                <div className="flex justify-between items-start">
                    <div>
                        <h2 className="text-2xl font-bold flex items-center gap-3">
                            {page_type.name}
                            <span className="text-xs bg-muted px-2 py-1 rounded-md text-muted-foreground font-normal">v{page_type.version}</span>
                        </h2>
                        <p className="text-muted-foreground mt-1">{page_type.description || 'No description.'}</p>
                    </div>
                    <Button asChild variant="outline">
                        <Link href={`/page-types/${page_type.id}/edit`}>Edit Page Type</Link>
                    </Button>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                    <Card>
                        <CardHeader>
                            <CardTitle>Details</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <h4 className="text-sm font-medium text-muted-foreground mb-1">Slug</h4>
                                <p className="text-sm">{page_type.slug}</p>
                            </div>
                            <div>
                                <h4 className="text-sm font-medium text-muted-foreground mb-1">Status</h4>
                                <span className={`inline-flex bg-opacity-20 text-xs px-2 py-1 rounded-full ${page_type.active ? 'bg-green-500 text-green-700 dark:text-green-400' : 'bg-red-500 text-red-700 dark:text-red-400'}`}>
                                    {page_type.active ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Related Pages</CardTitle>
                            <CardDescription>View all pages built with this type.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            {!pages && (
                                <Button onClick={loadPages} disabled={loading} variant="secondary">
                                    {loading ? 'Loading...' : 'Load Pages'}
                                </Button>
                            )}

                            {pages && (
                                <div className="space-y-3 mt-2">
                                    <h4 className="text-sm font-medium">Found {pages.length} page(s)</h4>
                                    {pages.length > 0 ? (
                                        <ul className="divide-y divide-border border rounded-md">
                                            {pages.map(page => (
                                                <li key={page.id} className="p-3 hover:bg-muted/50 transition-colors flex justify-between items-center">
                                                    <div>
                                                        <p className="font-medium text-sm">{page.name}</p>
                                                        <p className="text-xs text-muted-foreground">{page.slug}</p>
                                                    </div>
                                                    <Button variant="ghost" size="sm" asChild>
                                                        <Link href={`/pages/${page.id}/edit`}>View</Link>
                                                    </Button>
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                        <p className="text-sm text-muted-foreground">No pages use this type yet.</p>
                                    )}
                                </div>
                            )}
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
