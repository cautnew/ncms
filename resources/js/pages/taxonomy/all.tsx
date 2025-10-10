import { Head } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: '/settings/appearance',
    },
];

export default function Appearance() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="All Taxonomy" />

            <TaxonomyLayout>
                <div className="space-y-6">
                    <HeadingSmall title="All Taxonomy" description="Update your taxonomy" />
                    <p>Vamos ao teste</p>
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
