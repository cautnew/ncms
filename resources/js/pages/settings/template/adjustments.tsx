import { type BreadcrumbItem, type SharedData } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, useForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Template adjustments',
        href: '/settings/template/adjustments',
    },
];

type ProfileForm = {
    alias: string;
    name: string;
    description: string;
    version: string;
};

export default function Profile({ alias, name, description, version }: { alias: string; name: string; description: string; version: string }) {
    const { auth } = usePage<SharedData>().props;

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm<Required<ProfileForm>>({
        alias: alias,
        name: name,
        description: description,
        version: version,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('settings.template.adjustments.update'), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Template settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Template adjustments" description="Make your adjustments to your current template" />

                    <form onSubmit={submit} className="space-y-3">
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>

                            <Input
                                id="name"
                                className="mt-1 block w-full"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                                autoComplete="name"
                                placeholder="Template name"
                            />

                            <InputError className="mt-2" message={errors.name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="description">Description</Label>

                            <Input
                                id="description"
                                type="description"
                                className="mt-1 block w-full"
                                value={data.description}
                                onChange={(e) => setData('description', e.target.value)}
                                required
                                autoComplete="username"
                                placeholder="Template description"
                            />

                            <InputError className="mt-2" message={errors.description} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="version">Version</Label>

                            <Input
                                id="version"
                                type="version"
                                className="mt-1 block w-full"
                                value={data.version}
                                onChange={(e) => setData('version', e.target.value)}
                                required
                                autoComplete="username"
                                placeholder="Template version"
                            />

                            <InputError className="mt-2" message={errors.version} />
                        </div>

                        <div className="flex items-center gap-4">
                            <Button disabled={processing}>Save</Button>

                            <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-neutral-600">Saved</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
