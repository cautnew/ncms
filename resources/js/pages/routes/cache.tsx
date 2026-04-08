import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, useForm, Link } from '@inertiajs/react';
import { ArrowLeft, Trash2, RefreshCw, Info } from 'lucide-react';
import * as React from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Routes',
        href: '/routes',
    },
    {
        title: 'Cache details',
        href: '/routes/cache',
    },
];

export default function CacheDetails() {
    const { isCached, lastCachedAt } = usePage().props as any;
    const { post, delete: destroy, processing } = useForm();

    const handleGenerate = () => {
        post(route('routes.cache'));
    };

    const handleClear = () => {
        if (
            confirm(
                'Clear the route cache? Without it routes load dynamically on every request; in production this can hurt performance.',
            )
        ) {
            destroy(route('routes.cache.clear'));
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Route cache details" />
            <div className="flex h-full flex-1 flex-col mx-auto max-w-4xl gap-6 rounded-xl p-6">
                <div>
                    <Button variant="outline" asChild className="mb-6">
                        <Link href={route('routes')}>
                            <ArrowLeft className="mr-2 h-4 w-4" /> Back to routes
                        </Link>
                    </Button>

                    <h1 className="text-2xl font-bold mb-2">Advanced route cache management</h1>
                    <p className="text-muted-foreground text-sm">
                        View and control Laravel’s compiled flat route file.
                    </p>
                </div>

                <div className="grid gap-6 md:grid-cols-2">
                    {/* Status Card */}
                    <div className="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 className="text-lg font-semibold flex items-center gap-2 mb-4">
                            <Info className="h-5 w-5 text-blue-500" /> Current status
                        </h2>

                        <div className="space-y-4">
                            <div>
                                <p className="text-sm font-medium text-muted-foreground">File state</p>
                                <div className="mt-1 flex items-center">
                                    <div className={`h-3 w-3 rounded-full mr-2 ${isCached ? 'bg-green-500' : 'bg-red-500'}`}></div>
                                    <span className="font-semibold">
                                        {isCached ? 'Cache present (active)' : 'No cache (dynamic runtime)'}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <p className="text-sm font-medium text-muted-foreground">Last updated</p>
                                <p className="mt-1 font-semibold">{lastCachedAt ? lastCachedAt : 'Never'}</p>
                            </div>
                        </div>

                        <div className="mt-8 flex flex-col gap-3">
                            <Button
                                onClick={handleGenerate}
                                disabled={processing}
                                className="w-full flex items-center justify-center gap-2"
                            >
                                <RefreshCw className={`h-4 w-4 ${processing ? 'animate-spin' : ''}`} />
                                {isCached ? 'Rebuild cache' : 'Generate cache now'}
                            </Button>

                            {isCached && (
                                <Button
                                    onClick={handleClear}
                                    disabled={processing}
                                    variant="destructive"
                                    className="w-full flex items-center justify-center gap-2"
                                >
                                    <Trash2 className="h-4 w-4" />
                                    Clear existing cache
                                </Button>
                            )}
                        </div>
                    </div>

                    {/* Explainer Card */}
                    <div className="rounded-xl border bg-secondary/30 p-6 shadow-sm">
                        <h2 className="text-lg font-semibold mb-3">How does route caching work?</h2>
                        <div className="space-y-4 text-sm text-muted-foreground leading-relaxed">
                            <p>
                                Laravel registers routes by reading all definition files (web.php, api.php, etc.). On apps with
                                dozens or hundreds of endpoints, that work can repeat on <strong>every request.</strong>
                            </p>
                            <p>
                                When you build the <strong>route cache</strong>, the app serializes the full map and saves it to
                                disk as a static array (often <code>bootstrap/cache/routes-v7.php</code>). Laravel then skips
                                recompiling route definitions on each request.
                            </p>
                            <div className="bg-orange-500/10 border-l-4 border-orange-500 text-orange-800 dark:text-orange-200 p-3 rounded">
                                <strong>Note:</strong> Whenever you change, remove, or add routes in code, clear or rebuild the
                                cache here. Cached routes do not reflect code changes until you do.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
