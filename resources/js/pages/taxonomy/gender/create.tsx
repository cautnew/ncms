import AppLayout from '@/layouts/app-layout';
import TaxonomyLayout from '@/layouts/taxonomy/layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gender Taxonomy',
        href: '/taxonomy/gender',
    },
    {
        title: 'Create',
        href: '/taxonomy/gender/create',
    },
];

export default function GenderTaxonomy() {
    // const { errors, put, reset, processing, recentlySuccessful } = useForm({
    //     current_password: '',
    //     password: '',
    //     password_confirmation: '',
    // });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Gender Taxonomy" />

            <TaxonomyLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Gender Taxonomy" description="Genders available for the system." />
                </div>
            </TaxonomyLayout>
        </AppLayout>
    );
}
