import { Form, Head, usePage } from '@inertiajs/react';
import { Link } from '@inertiajs/react';
import WebsitesSettingsController from '@/actions/App/Http/Controllers/Settings/WebsitesSettingsController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import type { Auth } from '@/types';

type WebsitesCreateType = {
    auth: Auth;
    processing?: boolean;
    errors?: any;
};

export default function WebsitesCreate({
    auth,
    processing,
    errors,
}: WebsitesCreateType) {
    return (
        <>
            <div className="grid gap-2">
                <Label htmlFor="name">Name</Label>

                <Input
                    id="name"
                    className="mt-1 block w-full"
                    defaultValue={auth.user.name}
                    name="name"
                    required
                    autoComplete="name"
                    placeholder="Full name"
                />

                <InputError
                    className="mt-2"
                    message={errors.name}
                />
            </div>

            <div className="grid gap-2">
                <Label htmlFor="email">Email address</Label>

                <Input
                    id="email"
                    type="email"
                    className="mt-1 block w-full"
                    defaultValue={auth.user.email}
                    name="email"
                    required
                    autoComplete="username"
                    placeholder="Email address"
                />

                <InputError
                    className="mt-2"
                    message={errors.email}
                />
            </div>

            <div className="flex items-center gap-4">
                <Button
                    disabled={processing}
                    data-test="add-website-button"
                >
                    Add website
                </Button>
            </div>
        </>
    );
}

WebsitesCreate.layout = {
    breadcrumbs: [
        {
            title: 'Websites create',
            href: edit(),
        },
    ],
};
