import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Template settings', href: '/settings/template' }];

export default function Template({ template_name, description, version }: { template_name?: string; description?: string; version?: string }) {
    const { auth } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Template settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Templace information" description="Update the data of your current template" />

                    <div className="space-y-2 rounded-md bg-zinc-300 p-4 dark:bg-zinc-800">
                        <h4 className="text-lg font-bold">Your current template</h4>
                        <hr className="my-3 border-zinc-400" />
                        <h5 className="mt-3 mb-1 text-base font-bold">Name</h5>
                        <p className="text-sm">{template_name}</p>
                        <h5 className="mt-3 mb-1 text-base font-bold">Description</h5>
                        <p className="text-sm">{description}</p>
                        <h5 className="mt-3 mb-1 text-base font-bold">Version</h5>
                        <p className="text-sm">{version}</p>
                    </div>

                    <div className="grid gap-2 sm:grid-cols-1 md:grid-cols-2">
                        <Button className="text-dark bg-amber-300 dark:text-gray-900">
                            <Link href="settings/template/list">List of available templates</Link>
                        </Button>
                        <Button className="bg-amber-700">
                            <Link href="settings/template/adjustments">Make Adjustmetns</Link>
                        </Button>
                    </div>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
