import { Head, Link } from '@inertiajs/react';
import Heading from '@/components/heading';
import { edit } from '@/routes/profile';
import { create } from '@/routes/settings/websites/index';
import WebsitesIndexListTable from './index-listtable';
import { Button } from '@/components/ui/button';

export default function Websites({websites_list}: {websites_list: any}) {
    return (
        <>
            <Head title="Websites settings" />

            <h1 className="sr-only">Website settings</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Websites"
                    description="Manage your website settings"
                />
                <div className="flex items-center justify-end gap-4">
                    <Link href={create()}><Button>Create a Website</Button></Link>
                </div>
                <WebsitesIndexListTable websites_list={websites_list} />
            </div >
        </>
    );
}

Websites.layout = {
    breadcrumbs: [
        {
            title: 'Websites settings',
            href: edit(),
        },
    ],
};
