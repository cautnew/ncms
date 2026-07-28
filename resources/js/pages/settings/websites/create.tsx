import { Form, Head, Link, usePage } from '@inertiajs/react';
import WebsitesSettingsController from '@/actions/App/Http/Controllers/Settings/WebsitesSettingsController';
import Heading from '@/components/heading';
import { edit } from '@/routes/profile';
import { index } from '@/routes/settings/websites';
import { Button } from '@/components/ui/button';
import type { Auth } from '@/types';
import FormCreateWebsite from './create-form';

type PageProps = {
    auth: Auth;
};

export default function Websites({
    mustVerifyEmail,
    status,
}: {
    mustVerifyEmail: boolean;
    status?: string;
}) {
    const { auth } = usePage<PageProps>().props;

    return (
        <>
            <Head title="Websites create" />

            <h1 className="sr-only">Website create</h1>

            <div className="space-y-6">
                <Heading
                    variant="small"
                    title="Websites"
                    description="Update websites settings"
                />
                <div className="flex items-center justify-end gap-4">
                    <Link href={index()}><Button>Return</Button></Link>
                </div>
                <Form
                    {...WebsitesSettingsController.update.form()}
                    options={{
                        preserveScroll: true,
                    }}
                    className="space-y-6"
                >
                    {({processing, errors}) => (
                        <FormCreateWebsite auth={auth} processing={processing} errors={errors} />
                    )}
                </Form>
            </div>
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
