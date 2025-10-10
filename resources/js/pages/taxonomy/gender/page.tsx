import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { columns, Gender } from './columns';
import { DataTable } from './data-table';

import HeadingSmall from '@/components/heading-small';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gender Taxonomy',
        href: '/taxonomy/gender',
    },
];

export default function GenderTaxonomy({ gender_list }: { gender_list: Array<Gender> }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Gender Taxonomy" />

            <TaxonomyLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Gender Taxonomy" description="Genders available for the system." />
                    <DataTable columns={columns} data={gender_list} />
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
