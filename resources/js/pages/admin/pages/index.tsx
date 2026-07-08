import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';

type PageRow = {
    page: string;
    label: string;
    href: string;
};

export default function PagesIndex({ pages }: { pages: PageRow[] }) {
    return (
        <>
            <Head title="Páginas" />

            <div className="space-y-6 p-4">
                <Heading
                    title="Páginas"
                    description="Gerencie os blocos de conteúdo de cada página do site."
                />

                <div className="grid gap-4 md:grid-cols-2">
                    {pages.map((page) => (
                        <div
                            key={page.page}
                            className="flex items-center justify-between rounded-lg border p-4"
                        >
                            <span className="font-medium">{page.label}</span>
                            <Button variant="outline" asChild>
                                <Link href={page.href}>Gerenciar blocos</Link>
                            </Button>
                        </div>
                    ))}
                </div>
            </div>
        </>
    );
}

PagesIndex.layout = {
    breadcrumbs: [{ title: 'Páginas', href: '' }],
};
