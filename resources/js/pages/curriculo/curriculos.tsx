import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import CurriculoLayout from '@/layouts/curriculo/layout';

import { Button } from '@/components/ui/button';
import { Card, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Currículos',
        href: '/ncms/curriculos',
    },
];

export default function Ponto() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Currículos" />

            <CurriculoLayout>
                <div className="space-y-3">
                    <HeadingSmall title="Meus currículos" description="Seus currículos cadastrados." />
                    <div className="flex justify-end">
                        <Button asChild>
                            <Link href="curriculo/add" prefetch>
                                + Add new currículo
                            </Link>
                        </Button>
                    </div>
                    <div>
                        <Card className="mb-2" data-version="">
                            <CardHeader>
                                <CardTitle>Nome do currículo</CardTitle>
                                <CardDescription>Descrição do currículo</CardDescription>
                            </CardHeader>
                            <CardFooter />
                        </Card>
                    </div>
                </div>
            </CurriculoLayout>
        </AppLayout>
    );
}
