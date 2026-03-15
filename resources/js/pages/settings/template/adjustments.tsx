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

type AdjustmentForm = {
    alias: string;
    template_name: string;
    description: string;
    version: string;
    teste?: string;
};

type AdjustmentType = {
    alias: string;
    template_name: string;
    description: string;
    version: string;
    teste?: string;
};

const AdjustmentPage = ({ alias, template_name, description, version, teste }: AdjustmentForm) => {
    const { auth, other_params } = usePage<SharedData>().props;
    const template_params = usePage().props.template_params as AdjustmentType;

    const { data, setData, post, errors, processing, recentlySuccessful } = useForm<Required<AdjustmentForm>>({
        alias: alias || template_params.alias || '',
        template_name: template_name || template_params.template_name || '',
        description: description || template_params.description || '',
        version: version || template_params.version || '',
        teste: teste || template_params.teste || '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        console.log('e', e);
        console.log('e.target', e.target);

        post(route('settings.template.adjustments.update'), {
            preserveScroll: true,
        });
    };

    const component = (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Template settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Template adjustments" description="Make your adjustments to your current template" />

                    <form onSubmit={submit} className="space-y-3">
                        <div className="grid gap-2">
                            <Label htmlFor="template_name">Name</Label>

                            <Input
                                id="template_name"
                                className="mt-1 block w-full"
                                value={data.template_name}
                                onChange={(e) => setData('template_name', e.target.value)}
                                required
                                autoComplete="template_name"
                                placeholder="Template name"
                            />

                            <InputError className="mt-2" message={errors.template_name} />
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

                        <div className="grid gap-2">
                            <Label htmlFor="test">Teste</Label>

                            <Input
                                id="test"
                                className="mt-1 block w-full"
                                onChange={(e) => {
                                    e.target.value;
                                }}
                                required
                                autoComplete="test"
                                placeholder="Test"
                            />

                            <InputError className="mt-2" message={errors.teste} />
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

    return component;
};

export default AdjustmentPage;
