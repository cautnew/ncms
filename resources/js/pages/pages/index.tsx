import { Head } from '@inertiajs/react';
import { index as pages } from '@/routes/pages';

export default function Pages({ page_list }: { page_list?: any[] }) {
    return ( <>
        <Head title="Pages" />
        <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <table>
                <tbody>
                    {page_list && page_list.map( page => (<tr data-page-id={page.id}>
                        <td>{page.website_name}</td>
                        <td>{page.layout_name}</td>
                        <td>{page.slug}</td>
                        <td>{page.name}</td>
                        <td>{page.created_at}</td>
                        <td>{page.updated_at}</td>
                    </tr>) )} 
                </tbody>
            </table>
        </div>
    </> );
}

Pages.layout = {
    breadcrumbs: [
        {
            title: 'Pages',
            href: pages(),
        },
    ],
};
