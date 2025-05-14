import { Button } from '@/components/ui/button';
import { Form, FormControl, FormDescription, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/simple-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { useForm } from 'react-hook-form';
import { z } from 'zod';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Curriculos',
        href: '/ncms/curriculos',
    },
    {
        title: 'Add Curriculo',
        href: '/ncms/curriculo/add',
    },
];

const formSchema = z.object({
    curriculoName: z.string().min(3).max(50),
    firstName: z.string().min(3).max(50),
});

export default function Dashboard() {
    const form = useForm<z.infer<typeof formSchema>>();
    const submitForm = (values: z.infer<typeof formSchema>) => {
        console.log('Values submited:', values);
        console.log('Events available:', form);
    };

    return (
        <AppLayout
            breadcrumbs={breadcrumbs}
            title="Add Currículo"
            description="Adicione um currículo ao seu perfil. Os dados podem ser atualizados a qualquer momento."
        >
            <Head title="Add Curriculo" />
            <Form {...form}>
                <form onSubmit={form.handleSubmit(submitForm)} className="space-y-4">
                    <FormField
                        control={form.control}
                        name="curriculoName"
                        render={({ field }) => (
                            <FormItem>
                                <FormLabel>Currículo Name</FormLabel>
                                <FormControl>
                                    <Input placeholder="Currículo Name" {...field} />
                                </FormControl>
                                <FormDescription>This is the curriculo name, it will be available to you search for your curriculo.</FormDescription>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                    <FormField
                        control={form.control}
                        name="firstName"
                        render={({ field }) => (
                            <FormItem>
                                <FormLabel>First Name</FormLabel>
                                <FormControl>
                                    <Input placeholder="First Name" {...field} />
                                </FormControl>
                                <FormDescription>This is the curriculo name, it will be available to you search for your curriculo.</FormDescription>
                                <FormMessage />
                            </FormItem>
                        )}
                    />
                    <Button type="submit">Create</Button>
                </form>
            </Form>
        </AppLayout>
    );
}
