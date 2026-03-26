import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pages',
        href: '/pages',
    },
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pages" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex justify-between items-center">
                    <h3 className="text-sm font-semibold">Check the list of all of your pages.</h3>
                    <div className="flex gap-2">
                        <Button asChild variant="outline">
                            <Link href="/page-types/create">Create Page Type</Link>
                        </Button>
                        <Button asChild>
                            <Link href="/pages/create">Create Page</Link>
                        </Button>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
