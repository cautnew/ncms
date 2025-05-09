import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import PontoLayout from '@/layouts/ponto/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Meu ponto',
        href: '/ncms/ponto',
    },
];

export default function Ponto() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Ponto" />

            <PontoLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Meu ponto" description="Acompanhe aqui o seu relógio de ponto" />
                </div>
            </PontoLayout>
        </AppLayout>
    );
}
